<?php

namespace App\Http\Resources\Classified;

use App\Http\Resources\Project\LocationResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

class ProjectDataResource extends JsonResource
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
            'project_area_to' => $this->project_area_to,
            'project_price' => $this->project_price,
            'project_url' => $this->project_url,
            'project_name' => $this->project_name,
            // 'price_unit_id' => $this->price_unit_id,
            // 'project_unit_rent_id' => $this->project_unit_rent_id,
            'unit_price_id' => $this->when('unit_price_id', $this->unit_price_id),
            'direction' => $this->project_direction,
            'meta_title' => $this->meta_title,
            'meta_key' => $this->meta_key,
            'meta_desc' => $this->meta_desc,
            'images' => $this->getImages($this->image_url),
            'dependency_paradigm_id' => $this->when('dependency_paradigm_id', $this->dependency_paradigm_id),
            'location' => $this->when($this->location, new LocationResource($this->location)),
        ];
    }

    private function getImages($imageUrl)
    {
        if (!$imageUrl) return [];

        $imagesPath = [];

        $images = json_decode($imageUrl);

        foreach ($images as $path) {
            if (File::exists(public_path($path)))
                $imagesPath[] = asset($path);
        }

        return $imagesPath;
    }
}
