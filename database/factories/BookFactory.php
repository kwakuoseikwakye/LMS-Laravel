<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'author' => $this->faker->name(),
            'category_id' => Category::factory(),
            'cover_image' => 'books/default.jpg',
            'publication_year' => $this->faker->year(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
