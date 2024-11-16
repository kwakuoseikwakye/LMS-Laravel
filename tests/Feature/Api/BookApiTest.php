<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookApiTest extends TestCase
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

    public function test_can_list_books(): void
    {
        Book::factory(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/books');
        
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'author',
                            'description',
                            'publication_year',
                            'category' => [
                                'id',
                                'name'
                            ],
                            'cover_image',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ]);
    }

    public function test_can_show_book(): void
    {
        $book = Book::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'author',
                    'description',
                    'publication_year',
                    'category',
                    'cover_image',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_can_create_book(): void
    {
        Storage::fake('public');

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/books', [
            'title' => 'API Test Book',
            'author' => 'API Test Author',
            'description' => 'API Test Description',
            'publication_year' => 2024,
            'category_id' => $this->category->id,
            'cover_image' => UploadedFile::fake()->image('book.jpg')
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'author',
                    'description',
                    'publication_year',
                    'category',
                    'cover_image',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'API Test Book',
            'user_id' => $this->user->id
        ]);
    }

    public function test_validation_errors_return_correct_response(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/books', [
            'title' => '',  // Invalid - required
            'author' => '',  // Invalid - required
            'publication_year' => 'invalid'  // Invalid - must be integer
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'VALIDATION_ERROR',
                'message' => 'Book validation failed. The title field is required.. The author field is required.. The description field is required.. The publication year field must be an integer.. The publication year field must be at least 1000.. The category id field is required.'
            ]);
    }

    public function test_cannot_update_others_book(): void
    {
        $otherUser = User::factory()->create();
        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->putJson("/api/v1/books/{$book->id}", [
            'title' => 'Updated API Book',
            'author' => 'Updated API Author',
            'description' => 'Updated API Description',
            'publication_year' => 2024,
            'category_id' => $this->category->id
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'code' => 'FORBIDDEN',
                'message' => 'You are not authorized to update this book'
            ]);
    }

    public function test_can_delete_own_book(): void
    {
        $book = Book::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book deleted successfully'
            ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cannot_delete_others_book(): void
    {
        $otherUser = User::factory()->create();
        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(403)  // Changed from 400 to 403 (Forbidden)
            ->assertJson([
                'success' => false,
                'code' => 'FORBIDDEN',
                'message' => 'You are not authorized to delete this book'
            ]);

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }
}
