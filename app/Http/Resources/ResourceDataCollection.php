<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceDataCollection extends ResourceCollection
{
    protected array $pagination;

    /**
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'links' => $resource->linkCollection(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ResourceDataResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->pagination,
        ];
    }
}
