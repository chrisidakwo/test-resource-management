<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\Contracts\ResourceService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected ResourceService $resourceService;

    public function __construct()
    {
        $this->resourceService = resolve(ResourceService::class);
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        return view('admin.index');
    }
}
