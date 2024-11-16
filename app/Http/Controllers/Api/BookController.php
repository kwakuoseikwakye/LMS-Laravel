<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $books = Book::with(['category', 'user'])->paginate(5);
        return apiSuccessResponse('Books fetched successfully.', 200, BookResource::collection($books)->response()->getData(true));
    }

    public function show($id)
    {
        $book = Book::with(['category', 'user'])->find($id);

        if (!$book) {
            return apiErrorResponse('Book not found', 404);
        }
        return apiSuccessResponse('Book fetched successfully.', 200, new BookResource($book));
    }

    public function store(BookRequest $bookRequest)
    {
        $data = $bookRequest->validated();

        if ($bookRequest->hasFile('cover_image')) {
            $data['cover_image'] = $bookRequest->file('cover_image')->store('covers', 'public');
        }

        $book = $this->request->user()->books()->create($data);

        return apiSuccessResponse('Book created successfully.', 201, new BookResource($book));
    }

    public function update(BookRequest $bookRequest, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return apiErrorResponse('Book not found', 404);
        }

        if ($book->user_id !== $this->request->user()->id) {
            return apiErrorResponse('You are not authorized to update this book', 403);
        }

        $data = $bookRequest->validated();

        if ($bookRequest->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $bookRequest->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return apiSuccessResponse('Book updated successfully.', 200, new BookResource($book));
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return apiErrorResponse('Book not found', 404);
        }

        if ($book->user_id !== $this->request->user()->id) {
            return apiErrorResponse('You are not authorized to delete this book', 403);
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return apiSuccessResponse('Book deleted successfully', 200);
    }
}
