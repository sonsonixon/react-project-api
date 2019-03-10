<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait HasUuid {

	public static function bootHasUuid()
    {
        static::saving(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::generate(4);
        });
    }

}