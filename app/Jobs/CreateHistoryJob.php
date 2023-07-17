<?php

namespace App\Jobs;

use App\Enums\HistoryAction;
use App\Services\HistoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $action;
    public $data;
    public $attributes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $action, $data = [], $attributes = [])
    {
        $this->model = $model;
        $this->action = $action;
        $this->data = $data;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->model || !$this->action) return;

        switch ($this->action) {
            case HistoryAction::CREATED:
                HistoryService::createCreatedHistory($this->model, $this->data);
                break;
            case HistoryAction::UPDATED:
                HistoryService::createUpdatedHistory($this->model, $this->data, $this->attributes);
                break;
            case HistoryAction::DELETED:
                HistoryService::createUpdatedHistory($this->model, $this->data, $this->attributes, HistoryAction::DELETED);
                break;
            case HistoryAction::RESTORED:
                HistoryService::createUpdatedHistory($this->model, $this->data, $this->attributes, HistoryAction::RESTORED);
                break;
            case HistoryAction::FORCE_DELETED:
                HistoryService::createUpdatedHistory($this->model, $this->data, $this->attributes, HistoryAction::FORCE_DELETED);
                break;
            default:
        }
    }
}
