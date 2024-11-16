<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create();
        $categories = Category::all();

        for ($i = 1; $i <= 10; $i++) {
            Book::create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'title' => "Sample Book $i",
                'author' => "Author $i",
                'description' => "Description for book $i",
                'publication_year' => rand(2000, 2023)
            ]);
        }
    }
}
