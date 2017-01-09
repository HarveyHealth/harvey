<?php

namespace August\Pitbull\Models;

class PBUser
{
    static $user_model;

    static function findOrFail($user_id) {
        $model = self::model();
        return $model::findOrFail($user_id);
    }

    static function all() {
        $model = self::model();
        return $model::orderBy('id','DESC')->paginate(50);
    }

    static function model()
    {
        if  (empty(self::$user_model)) {

            $class = config('pitbull.user_model');

            if (!class_exists($class))
                throw new \Exception('Invalid Pitbull User model class: ' . $class . '. Please update your Pitbull config.');

            self::$user_model = $class;
        }

        return self::$user_model;
    }
}
