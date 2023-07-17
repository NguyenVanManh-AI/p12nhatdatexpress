<?php

namespace App\Traits\Models;

trait TimestampTrait
{
    public static function bootTimestampTrait()
    {
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?: time();
            $model->updated_at = time();
        });

        static::updating(function ($model) {
            $model->updated_at = time();
        });

        static::deleting(function ($model) {
            $model->updated_at = time();
        });

        static::restoring(function ($model) {
            $model->updated_at = time();
        });
    }
}
