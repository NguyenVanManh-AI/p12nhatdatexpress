<?php

namespace App\Services;

use App\Enums\HistoryAction;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryService
{
    /**
     * create created history
     * @param $model
     * @param array $data
     * 
     * @return void
     */
    public static function createCreatedHistory($model, array $data)
    {
        History::create([
            'historyable_type' => mb_convert_encoding(get_class($model), 'UTF-8'),
            'historyable_id' => data_get($model, 'id'),
            'action' => HistoryAction::CREATED,
            'attributes' => array_map("utf8_encode", $model->getAttributes()),
            'action_admin_id' => data_get($data, 'action_admin_id'),
            'action_user_id' => data_get($data, 'action_user_id'),
        ]);
    }

    /**
     * create updated|deleted|restored history
     * @param $model
     * @param array $data
     * @param array $attributes
     * @param string $action
     * 
     * @return void
     */
    public static function createUpdatedHistory($model, array $data, array $attributes, $action = HistoryAction::UPDATED)
    {
        History::create([
            'historyable_type' => mb_convert_encoding(get_class($model), 'UTF-8'),
            'historyable_id' => data_get($model, 'id'),
            'action' => $action,
            'attributes' => $attributes,
            'action_admin_id' => data_get($data, 'action_admin_id'),
            'action_user_id' => data_get($data, 'action_user_id'),
        ]);
    }
}
