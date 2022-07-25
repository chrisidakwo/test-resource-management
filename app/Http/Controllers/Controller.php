<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create and return a JSON response.
     *
     * @param array|Collection $data
     * @param int $status
     * @return JsonResponse
     */
    public function response(array|Collection $data, int $status = 200): JsonResponse
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        return new JsonResponse($data, $status);
    }
}
