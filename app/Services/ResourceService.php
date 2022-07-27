<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ResourceService implements Contracts\ResourceService
{
    /**
     * @inheritDoc
     */
    public function listResources(int $page, int $limit = 20): AbstractPaginator
    {
        $limit = ($limit > 50) ? 50 : $limit;

        return Resource::query()
            ->latest()
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function createResource(string $type, array $data): Model|Resource
    {
        $properties = $this->getPropertiesByResourceType($type, $data);

        $resource = Resource::query()->create($properties);

        if ($type === Resource::TYPE_PDF) {
            $resource->file = $this->handlePdfUpload($data['file'], $resource->id);
            $resource->save();
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function viewResource(string $resourceId): Resource
    {
        return $this->findResource($resourceId);
    }

    /**
     * @inheritDoc
     */
    public function updateResource(string $resourceId, array $data): Resource
    {
        $resource = $this->findResource($resourceId);

        if ($resource->type === Resource::TYPE_PDF && array_key_exists('file', $data)) {
            $resource->file = $this->handlePdfUpload($data['file'], $resource->id);

            unset($data['file']);
        }

        $resource->fill($data)->save();

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function deleteResources(array $resources): ?bool
    {
        return Resource::query()->whereIn('id', $resources)->delete();
    }

    /**
     * Find a resource where it exists. Else, throw a RuntimeException.
     *
     * @param string $resourceId
     * @return Model|Resource
     */
    protected function findResource(string $resourceId): Model|Resource
    {
        try {
            $resource = Resource::query()->where([
                'id' => $resourceId
            ])->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            throw new RuntimeException('Could not find resource', $ex->getCode(), $ex);
        }

        return $resource;
    }

    /**
     * @param string $type
     * @param array $data
     * @return array
     */
    protected function getPropertiesByResourceType(string $type, array $data): array
    {
        $properties = match ($type) {
            Resource::TYPE_HTML => [
                'description' => $data['description'] ?? null,
                'html_snippet' => $data['html_snippet'],
            ],

            Resource::TYPE_PDF => [
                'file' => $data['file'],
            ],

            Resource::TYPE_LINK => [
                'link' => $data['link'],
                'link_target' => $data['link_target'] ?? '_parent',
            ],
        };

        $properties['title'] = $data['title'];
        $properties['type'] = $type;

        return $properties;
    }

    /**
     * @param UploadedFile|File $file
     * @param int $resourceId
     * @return string
     */
    protected function handlePdfUpload(File|UploadedFile $file, int $resourceId): string
    {
        return Storage::disk('public')->putFile("/resources/$resourceId", $file);
    }
}
