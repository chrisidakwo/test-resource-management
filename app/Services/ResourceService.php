<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ResourceService implements Contracts\ResourceService
{
    /**
     * @param int $page
     * @param int $limit
     * @return Collection
     */
    public function list(int $page, int $limit = 20): Collection
    {
        $resources = Resource::query()
            ->latest()
            ->paginate($limit, ['*'], 'page', $page)
            ->toArray();

        $resourcesData = $resources['data'];
        unset($resources['data']);

        return collect([
            'data' => $resourcesData,
            'pagination' => $resources
        ]);
    }

    public function search(string $query): Collection
    {
        // TODO: Implement search() method.
    }
}
