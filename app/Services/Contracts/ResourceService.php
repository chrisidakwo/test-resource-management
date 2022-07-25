<?php

namespace App\Services\Contracts;

use App\Models\Resource;
use Illuminate\Support\Collection;

interface ResourceService
{
    /**
     * @param int $page
     * @param int $limit
     * @return Collection<Resource>
     */
    public function list(int $page, int $limit = 50): Collection;

    /**
     * @param string $query
     * @return Collection
     */
    public function search(string $query): Collection;
}
