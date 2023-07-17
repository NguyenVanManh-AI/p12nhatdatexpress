<?php

namespace App\Http\Resources\Project;

use App\Helpers\Helper;
use App\Http\Resources\Comment\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent_children_count' => $this->parent ? $this->parent->children->count() : '',
            'content' => $this->comment_content,
            'created_at' => Helper::get_time_to($this->created_at),
            'user' => $this->when($this->user, new UserResource($this->user)),
        ];
    }
}
