<?php

namespace Tests\Feature;

use App\Models\Resource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ResourceApiControllerTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public function testItRetrievesResourcesWithPaginationData(): void
    {
        $response = $this->get(route('api.resources.index', [ 'page' => 1 ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'title',
                    'type',
                ]
            ],
            'pagination' => [
                'total',
                'links' => [

                ]
            ]
        ]);
    }

    public function testItCreatesNewPdfResource(): void
    {
        $data = [
            'type' => Resource::TYPE_PDF,
            'title' => $this->faker->text(50),
            'file' => new File(base_path('tests/table.pdf'), true),
        ];

        $response = $this->post(route('api.resources.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.type', 'pdf');

        $data = $response->json()['data'];

        self::assertNotEmpty($data['file']);
    }

    public function testItValidatesAgainstNotPdfFiles(): void
    {
        $data = [
            'type' => Resource::TYPE_PDF,
            'title' => $this->faker->text(50),
            'file' => new File(base_path('tests/monkeys.jpeg'), true),
        ];

        $response = $this->postJson(route('api.resources.store'), $data);

        $response->assertStatus(422);

        $response->assertInvalid(['file']);

        $response->assertJsonValidationErrors([
            'file' => [
                'The file field is required when type is pdf.'
            ]
        ]);
    }
}
