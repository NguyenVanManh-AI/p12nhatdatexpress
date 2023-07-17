<?php

namespace App\Traits\Models;

use App\Enums\HistoryAction;
use App\Jobs\CreateHistoryJob;
use Illuminate\Support\Facades\Auth;

trait AdminHistoryTrait
{
    public static function bootAdminHistoryTrait()
    {
        static::created(function ($model) {
            if (!Auth::guard('admin')->user() || !Auth::guard('admin')->user()->id) return;
            $data['action_admin_id'] = Auth::guard('admin')->user()->id;

            CreateHistoryJob::dispatch($model, HistoryAction::CREATED, $data);
        });

        static::updated(function ($model) {
            if (!Auth::guard('admin')->user() || !Auth::guard('admin')->user()->id) return;
            $data['action_admin_id'] = Auth::guard('admin')->user()->id;

            // not deleted & restored
            if (count($model->getDirty()) && !$model->isDirty('is_deleted')) {
                CreateHistoryJob::dispatch($model, HistoryAction::UPDATED, $data, mb_convert_encoding($model->getDirty(), 'UTF-8', 'UTF-8'));
            }
        });

        static::deleting(function ($model) {
            if (!Auth::guard('admin')->user() || !Auth::guard('admin')->user()->id) return;
            $data['action_admin_id'] = Auth::guard('admin')->user()->id;

            if ($model->isForceDeleting()) {
                CreateHistoryJob::dispatch($model, HistoryAction::FORCE_DELETED, $data, array_map("utf8_encode", $model->getAttributes()));
            } else {
                CreateHistoryJob::dispatch($model, HistoryAction::DELETED, $data, array_map("utf8_encode", []));
            }
        });

        if (method_exists(self::class, 'restored')) {
            static::restored(function ($model) {
                if (!Auth::guard('admin')->user() || !Auth::guard('admin')->user()->id) return;
                $data['action_admin_id'] = Auth::guard('admin')->user()->id;

                CreateHistoryJob::dispatch($model, HistoryAction::RESTORED, $data, array_map("utf8_encode", []));
            });
        }
    }
}
