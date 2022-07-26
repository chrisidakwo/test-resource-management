<?php

namespace App\Http\Resources;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ResourceDataResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var string
     */
    public $resource = Resource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'htmlSnippet' => $this->html_snippet,
            'link' => $this->link,
            'linkTarget' => $this->link_target,
            'file' => $this->file ? asset("storage/$this->file"): $this->file,
        ];
    }
}
