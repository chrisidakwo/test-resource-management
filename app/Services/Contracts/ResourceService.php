<?php

namespace App\Services\Contracts;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use RuntimeException;

interface ResourceService
{
    /**
     * @param int $page
     * @param int $limit
     * @return AbstractPaginator
     */
    public function listResources(int $page, int $limit = 50): AbstractPaginator;

    /**
     * @param string $type
     * @param array $data
     * @return Model|Resource
     */
    public function createResource(string $type, array $data): Model|Resource;

    /**
     * @param string $resourceId
     * @return Resource
     * @throws RuntimeException
     */
    public function viewResource(string $resourceId): Resource;

    /**
     * @param string $resourceId
     * @param array $data
     * @return Resource
     * @throws RuntimeException
     */
    public function updateResource(string $resourceId, array $data): Resource;

    /**
     * @param string $resourceId
     * @return bool|null
     * @throws RuntimeException
     */
    public function deleteResource(string $resourceId): ?bool;
}
