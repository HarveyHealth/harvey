<?php

namespace App\Http\Traits;

use ReflectionClass;

trait HasLogTable
{
    protected static function bootHasLogTable()
    {
        $table_name = self::getModel()->getTable() . '_log';
        $class_name = (new ReflectionClass(self::getModel()))->getShortName() . 'LogEntry';
        eval("
            namespace App\Models;

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
                    ]);
                }
            }
        });
    }

    public function logEntries()
    {
        return $this->hasMany(self::class . 'LogEntry');
    }
}
