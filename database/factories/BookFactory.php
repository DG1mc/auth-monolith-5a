<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => Author::factory(),
            'title' => fake()->sentence(3),
            'isbn' => '978' . fake()->unique()->numerify('##########'),
            'published_year' => fake()->numberBetween(1900, (int) now()->year),
            'price' => fake()->randomFloat(2, 20000, 300000),
            'stock' => fake()->numberBetween(1, 20),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => ['stock' => 0]);
    }
}
