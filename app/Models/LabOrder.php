<?php

namespace App\Models;

use App\Http\Traits\{
    BelongsToPatientAndPractitioner,
    HasDiscountCodeIdColumn,
    HasStatusColumn,
    Invoiceable
};
use App\Models\{LabTest, SKU};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Models\DiscountCode;

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
}
