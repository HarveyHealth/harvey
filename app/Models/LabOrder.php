<?php

namespace App\Models;

use App\Http\Traits\{BelongsToPatientAndPractitioner, HasStatusColumn, Invoiceable};
use App\Lib\TimeInterval;
use App\Lib\Validation\StrictValidatorException;
use App\Models\{DiscountCode, LabTest, SKU};
use App\Http\Traits\{
    BelongsToPatientAndPractitioner,
    HasDiscountCodeIdColumn,
    HasStatusColumn,
    Invoiceable
};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Support\Facades\Redis;
use Exception, Shippo_Address, Shippo_CarrierAccount, Shippo_Transaction;

class LabOrder extends Model
{
    use SoftDeletes, HasDiscountCodeIdColumn, HasStatusColumn, BelongsToPatientAndPractitioner, Invoiceable;

    const CANCELED_STATUS_ID = 1;
    const COMPLETE_STATUS_ID = 7;
    const CONFIRMED_STATUS_ID = 2;
    const MAILED_STATUS_ID = 5;
    const PROCESSING_STATUS_ID = 6;
    const RECEIVED_STATUS_ID = 4;
    const RECOMMENDED_STATUS_ID = 0;
    const SHIPPED_STATUS_ID = 3;

    protected $dates = [
        'completed_at',
        'created_at',
        'deleted_at',
    ];

    protected $guarded = [
        'id',
        'completed_at',
        'created_at',
        'deleted_at',
        'shippo_id',
        'discount_code',
        'discount_code_id',
        'status_id',
    ];

    const STATUSES = [
        self::CANCELED_STATUS_ID => 'canceled',
        self::COMPLETE_STATUS_ID => 'complete',
        self::CONFIRMED_STATUS_ID => 'confirmed',
        self::MAILED_STATUS_ID => 'mailed',
        self::PROCESSING_STATUS_ID => 'processing',
        self::RECEIVED_STATUS_ID => 'received',
        self::RECOMMENDED_STATUS_ID => 'recommended',
        self::SHIPPED_STATUS_ID => 'shipped',
    ];

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function setStatus() {
        if ($this->labTests->isEmpty()) {
            return true;
        }

        if (1 == $this->labTests->pluck('status_id')->unique()->count()) {
            $this->status_id = $this->labTests->first()->status_id;
        } else {
            $this->status_id = $this->labTests->pluck('status_id')->diff([LabTest::CANCELED_STATUS_ID])->min();
        }

        return $this;
    }

    public function getShipmentLabelUrlAttribute()
    {
        if (empty($this->shippo_id)) {
            return null;
        }

        $redis_key = $this->redisKeyForUrlLabel();

        $label_url = Redis::get($redis_key);

        if (empty($label_url)) {
            $label_url = Shippo_Transaction::retrieve($this->shippo_id)->label_url ?? null;
            Redis::set($redis_key, $label_url);
            Redis::expire($redis_key, TimeInterval::days(rand(15, 20))->addHours(rand(0, 100))->toSeconds());
        }

        $seconds_before_retry = TimeInterval::minutes(10)->toSeconds();

        if (empty($label_url) && Redis::ttl($redis_key) > $seconds_before_retry) {
            Redis::expire($redis_key, $seconds_before_retry);
            ops_warning('LabOrdersController', "Error retrieving label_url key for LabOrder ID #{$this->id}, Shippo ID #{$this->shippo_id}");
        }

        return $label_url;
    }

    public function redisKeyForUrlLabel()
    {
        return "label_url_for_shippo_id_{$this->shippo_id}";
    }

    public function dataForInvoice()
    {
        $labTests = $this->labTests()->notCanceled()->get();

        if ($labTests->isEmpty()) {
            return [];
        }

        $invoiceData = [
            'patient_id' => $this->patient_id,
            'practitioner_id' => $this->practitioner_id,
            'discount_code_id' => $this->discount_code_id,
            'invoice_items' => [],
            'description' => "Lab Tests order #{$this->id} on " . date('n/j/Y'),
        ];

        $subtotal = 0;

        foreach ($labTests as $labTest) {
            $invoiceData['invoice_items'][] = [
                'amount' => $labTest->sku->price,
                'description' => "{$labTest->sku->name} Test",
                'item_class' => get_class($labTest),
                'item_id' => $labTest->id,
                'sku_id' => $labTest->sku->id,
            ];

            $subtotal += $labTest->sku->price;
        }

        $sku = SKU::findBySlugOrFail('processing-fee-self');

        $subtotal += $sku->price;

        $invoiceData['invoice_items'][] = [
            'amount' => $sku->price,
            'description' => $sku->name,
            'sku_id' => $sku->id,
        ];

        // if we have a discount code,
        // add another invoice item
        if ($this->discount_code_id) {

            $sku = SKU::findBySlugOrFail('discount');
            $discount_code = DiscountCode::find($this->discount_code_id);
            $amount = $discount_code->discountForSubtotal($subtotal);

            $invoiceData['invoice_items'][] = [
                'item_id' => $discount_code->id,
                'item_class' => get_class($discount_code),
                'description' => $discount_code->itemDescription(),
                'amount' => $amount,
                'sku_id' => $sku->id,
            ];
        }

        return $invoiceData;
    }

    public function ship()
    {
        if (!empty($this->shippo_id)) {
            return $this;
        }

        $user = $this->patient->user;

        $from = array_merge(config('services.shippo.from'), ['test' => isNotProd()]);

        $to = [
            'name' => $user->full_name,
            'company' => '',
            'street1' => $this->address_1,
            'street2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => 'US',
            'phone' => $user->phone,
            'email' => $user->email,
            'test' => isNotProd(),
        ];

        try {
            $shippo_address = Shippo_Address::create($to);
            $shippo_to_address_id = $shippo_address->object_id;

            if (!Shippo_Address::validate($shippo_to_address_id)->validation_results->is_valid) {
                throw new StrictValidatorException('The address' . json_encode($to) . 'is invalid.');
            }

            $parcel_info = $this->labTests->pluck('sku.attributes')->map(function($i) {
                return collect($i)->only(['length', 'width', 'height', 'distance_unit', 'weight', 'mass_unit']);
            });

            $carriers = Shippo_CarrierAccount::all(['carrier' => config('services.shippo.carrier')]);
            $carrier_object_id = $carriers->results[0]->object_id ?? null;

            if (empty($carrier_object_id)) {
                throw new Exception("Can't get carrier_object_id when processing LabOrder ID #{$this->id}");
            }

            $transaction = Shippo_Transaction::create([
                'shipment' => [
                    'address_to' => $shippo_to_address_id,
                    'address_from' => $from,
                    'parcels' => $parcel_info,
                ],
                'carrier_account' => $carrier_object_id,
                'servicelevel_token' => config('services.shippo.carrier_service_level'),
                'label_file_type' => 'PDF',
                'async' => false,
                'test' => isNotProd(),
            ]);

            if ('SUCCESS' != $transaction->status) {
                throw new Exception("Transaction failed when shipping LabOrder ID #{$this->id}. " . collect($transaction->messages)->implode('text', ' - '));
            }

            Redis::set($this->redisKeyForUrlLabel(), $transaction->label_url);

            $this->shippo_id = $transaction->object_id;
            $this->shipment_code = $transaction->tracking_number;

            $this->save();
        } catch (StrictValidatorException $e) {
            throw $e;
        } catch (Exception $e) {
            ops_warning('LabOrder@ship', $e->getMessage());
            return false;
        }

        return $this;
    }

}
