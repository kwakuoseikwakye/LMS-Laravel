<x-books-layout header="Books Management">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold">Books Collection</h2>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
        </div>

        <!-- Add Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="searchForm" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="category" class="form-label">Filter by Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Search books...">
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div id="loading" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="booksTable">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="100">Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Publication Year</th>
                                <th>Description</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>
                                        <img src="{{ $book->cover_image ? Storage::url($book->cover_image) : asset('storage/books/default.jpg') }}" 
                                             alt="{{ $book->title }}" 
                                             class="img-thumbnail"
                                             style="width: 80px; height: 100px; object-fit: cover;">
                                    </td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $book->category->name }}
                                        </span>
                                    </td>
                                    <td>{{ $book->publication_year }}</td>
                                    <td>{{ Str::limit($book->description, 50) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('books.edit', $book) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this book?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        No books found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $books->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const categorySelect = document.getElementById('category');
            const booksTable = document.getElementById('booksTable');

            // Function to update table
            function updateTable() {
                const loadingIndicator = document.getElementById('loading');
                loadingIndicator.classList.remove('d-none');

                const searchValue = searchInput.value;
                const categoryValue = categorySelect.value;

                // Create URL with parameters
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchValue);
                if (categoryValue) {
                    url.searchParams.set('category', categoryValue);
                } else {
                    url.searchParams.delete('category');
                }

                // Update URL without refresh
                window.history.pushState({}, '', url);

                // Fetch updated data
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.getElementById('booksTable');
                        booksTable.innerHTML = newTable.innerHTML;
                        loadingIndicator.classList.add('d-none');
                    });
            }

            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Add event listeners
            searchInput.addEventListener('input', debounce(updateTable, 300));
            categorySelect.addEventListener('change', updateTable);
        });
    </script>
</x-books-layout>
