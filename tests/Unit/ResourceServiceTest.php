<?php

namespace Tests\Unit;

use App\Models\Resource;
use App\Services\Contracts\ResourceService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Tests\TestCase;

class ResourceServiceTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    protected ResourceService $resourceService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceService = resolve(ResourceService::class);
    }

    public function testThatItGetsResources(): void
    {
        $resources = $this->resourceService->listResources(1, 10);

        $data = $resources->getCollection()->toArray();

        self::assertCount(10, $data);
        self::assertEquals(10, $resources->perPage());
    }

    public function testItCreatesNewResource(): void
    {
        $data = [
            'type' => Resource::TYPE_LINK,
            'title' => $this->faker->text(50),
            'link' => $this->faker->url,
            'link_target' => '_blank',
        ];

        $resource = $this->resourceService->createResource($data['type'], $data);

        self::assertInstanceOf(Resource::class, $resource);
        self::assertEquals('link', $resource->type);
        self::assertEquals('_blank', $resource->link_target);
    }

    public function testItCreatesNewPdfResource(): void
    {
        $data = [
            'type' => Resource::TYPE_PDF,
            'title' => $this->faker->text(50),
            'file' => new File(base_path('tests/table.pdf'), true),
        ];

        $resource = $this->resourceService->createResource($data['type'], $data);

        self::assertNotEmpty($resource->file);
        self::assertInstanceOf(Resource::class, $resource);
    }
}
