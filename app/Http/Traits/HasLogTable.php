<?php

namespace App\Http\Traits;

use Illuminate\Database\Schema\Blueprint;
use ReflectionClass;

trait HasLogTable
{
    protected static function bootHasLogTable()
    {
        $class_name = (new ReflectionClass(self::getModel()))->getShortName() . 'LogEntry';
        $models_namespace = 'App\Models';
        if (class_exists("{$models_namespace}\\{$class_name}")) {
            return;
        }
        $table_name = static::getLogTableName();
        eval("
            namespace {$models_namespace};

            use Illuminate\Database\Eloquent\{Model, SoftDeletes};

            class {$class_name} extends Model {
                use SoftDeletes;

                protected \$table = '{$table_name}';
                protected \$dates = [
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ];
                protected \$guarded = [
                    'id',
                    'created_at',
                    'deleted_at',
                    'updated_at',
                ];
            }
        ");

        static::updating(function ($model) {
            foreach ((array) $model->log as $attribute) {
                if (in_array($attribute, array_keys($model->getDirty()))) {
                    $model->logEntries()->create([
                        'attribute' => $attribute,
                        'from' => $model->getOriginal($attribute),
                        'to' => $model->$attribute,
                        'updated_by_user_id' => currentUser()->id ?? null,
                    ]);
                }
            }
        });
    }

    public function logEntries()
    {
        return $this->hasMany(self::class . 'LogEntry');
    }

    public static function getLogTableName()
    {
        return self::getModel()->getTable() . '_log';
    }

    public static function getLogTableSchema() {
        $model_foreign_key = self::getModel()->getForeignKey();
        $model_table_name = self::getModel()->getTable();

        return function (Blueprint $table) use ($model_foreign_key, $model_table_name) {
            $table->increments('id');
            $table->integer($model_foreign_key)->unsigned()->index();
            $table->string('attribute')->index();
            $table->string('from');
            $table->string('to');
            $table->integer('updated_by_user_id')->nullable()->unsigned()->index();
            $table->foreign($model_foreign_key)->references('id')->on($model_table_name);
            $table->foreign('updated_by_user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        };
    }
}
