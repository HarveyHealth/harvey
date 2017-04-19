<?php

namespace August\Pitbull\Models;

class PBUser
{
    public static $user_model;

    public static function findOrFail($user_id)
    {
        $model = self::model();
        return $model::findOrFail($user_id);
    }

    public static function all()
    {
        $model = self::model();
        return $model::orderBy('id', 'DESC')->paginate(50);
    }

    public static function model()
    {
        if (empty(self::$user_model)) {
            $class = config('pitbull.user_model');

            if (!class_exists($class)) {
                throw new \Exception('Invalid Pitbull User model class: ' . $class . '. Please update your Pitbull config.');
            }

            self::$user_model = $class;
        }

        return self::$user_model;
    }
}
