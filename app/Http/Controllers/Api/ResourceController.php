<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Resources\ResourceDataCollection;
use App\Http\Resources\ResourceDataResource;
use App\Services\Contracts\ResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    protected ResourceService $resourceService;

    /**
     * @param ResourceService $resourceService
     */
    public function __construct(ResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);

        $resources = $this->resourceService->listResources($page);

        return ResourceDataCollection::make($resources)->toResponse($request);
    }

    /**
     * @param StoreResourceRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $type = $request->get('type');

        $resourceData = $request->except(['type']);

        $resource = $this->resourceService->createResource($type, $resourceData);

        return (new ResourceDataResource($resource))->toResponse($request)->setStatusCode(201);
    }

    /**
     * @param Request $request
     * @param string $resourceId
     * @return JsonResponse
     */
    public function view(Request $request, string $resourceId): JsonResponse
    {
        $resource = $this->resourceService->viewResource($resourceId);

        return (new ResourceDataResource($resource))->toResponse($request);
    }

    public function download(string $resourceId)
    {

    }

    /**
     * @param Request $request
     * @param string $resourceId
     * @return JsonResponse
     */
    public function update(Request $request, string $resourceId): JsonResponse
    {
        $resource = $this->resourceService->updateResource($resourceId, $request->all());

        return (new ResourceDataResource($resource))->toResponse($request);
    }

    /**
     * @param string $resourceId
     * @return JsonResponse
     */
    public function delete(string $resourceId): JsonResponse
    {
        $result = $this->resourceService->deleteResource($resourceId);

        return $this->response([], ($result) ? 204 : 500);
    }
}
