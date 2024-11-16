<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_user_can_create_book(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/books', [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'publication_year' => 2023,
            'category_id' => $this->category->id,
            'cover_image' => UploadedFile::fake()->image('book.jpg')
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_can_update_own_book(): void
    {
        $book = Book::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)->put(route('books.update', $book), [  // Use route() helper
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'publication_year' => 2024,
            'category_id' => $this->category->id
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_user_cannot_update_others_book(): void
    {
        $otherUser = User::factory()->create();
        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->from('/books')
            ->put(route('books.update', $book), [  // Use route() helper
                'title' => 'Updated Title',
                'author' => 'Updated Author',
                'description' => 'Updated Description',
                'publication_year' => 2024,
                'category_id' => $this->category->id
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_user_can_delete_own_book(): void
    {
        $book = Book::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)->delete(route('books.destroy', $book));  // Use route() helper

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_user_cannot_delete_others_book(): void
    {
        $otherUser = User::factory()->create();
        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->from('/books')
            ->delete(route('books.destroy', $book));  // Use route() helper

        $response->assertStatus(403);
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_books_are_paginated(): void
    {
        Book::factory(6)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)->get('/books');

        $response->assertOk();
        $response->assertViewHas('books');
        $this->assertEquals(5, $response->original->getData()['books']->perPage());
    }
}
