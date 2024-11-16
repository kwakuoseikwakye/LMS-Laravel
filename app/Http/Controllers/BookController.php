<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Events\BookCreated;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Book::query()->with(['category', 'user']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $cacheKey = 'books.page.' . request('page', 1) .
            '.category.' . $request->input('category', 'all') .
            '.search.' . $request->input('search', 'none');

        $books = Cache::remember($cacheKey, 3600, function () use ($query) {
            return $query->latest()->paginate(5);
        });

        $categories = Category::all();

        if ($request->ajax()) {
            return view('books.index', compact('books', 'categories'))->render();
        }

        return view('books.index', compact('books', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(BookRequest $request)
    {
        $this->authorize('create', Book::class);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // $book = Auth::user()->books()->create($data);
        $data['user_id'] = Auth::user()->id;
        $book = Book::create($data);

        event(new BookCreated($book));

        Cache::flush();

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    public function edit($id)
    {
        $authUser = Auth::user()->id;
        $book = Book::where('user_id', $authUser)->find($id);

        if (!$book) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }

        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        // $book = $request->user()->books()->find($id);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        Cache::flush();

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        // $authUser = Auth::user()->id;
        // $book = Book::where('user_id', $authUser)->find($id);

        if (!$book) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        Cache::flush();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
