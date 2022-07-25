<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(50),
        ];
    }

    public function randomType(string $type): self
    {
        Log::info('Resource type', [
            'type' => $type,
        ]);

        $properties = match ($type) {
            Resource::TYPE_HTML => [
                'type' => 'html',
                'description' => $this->faker->realText(),
                'html_snippet' => $this->faker->randomHtml(6),
            ],

            Resource::TYPE_LINK => [
                'type' => 'link',
                'link' => $this->faker->url,
                'link_target' => $this->faker->randomElement(['_parent', '_blank']),
            ],

            Resource::TYPE_PDF => [
                'type' => 'pdf',
                'file' => $this->faker->url,
            ],
        };

        return $this->state(fn ($attributes) => $properties);
    }

    /**
     * Link resource type
     *
     * @return $this
     */
    public function link(): self
    {
        return $this->state(fn ($attributes) => [
            'type' => 'link',
            'link' => $this->faker->url,
            'link_target' => $this->faker->randomElement(['_parent', '_blank']),
        ]);
    }

    /**
     * PDF resource type
     *
     * @return $this
     */
    public function pdf(): self
    {
        return $this->state(fn ($attributes) => [
            'type' => 'pdf',
            'file' => $this->faker->url,
        ]);
    }

    /**
     * HTML resource type
     *
     * @return $this
     */
    public function html(): self
    {
        return $this->state(fn ($attributes) => [
            'type' => 'html',
            'description' => $this->faker->realText(),
            'html_snippet' => $this->faker->randomHtml(6),
        ]);
    }
}
