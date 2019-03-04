<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * 
     */
    protected static function boot()
    {
        parent::boot();
         /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `uid` field (provided by $model->getKeyName())
        **/
        static::creating(function ($model) {
        	
            $model->{$model->getKeyName()} = (string) $model->generateNewId();
        });
    }

 	/**
     * Get a new version 4 (random) UUID.
    **/
    public function generateNewId()
    {
        return Uuid::generate(4);
    }
}