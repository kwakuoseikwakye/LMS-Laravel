<div class="row g-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $book->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author"
                name="author" value="{{ old('author', $book->author ?? '') }}" required>
            @error('author')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="publication_year" class="form-label">Publication Year</label>
            <input type="number" class="form-control @error('publication_year') is-invalid @enderror"
                id="publication_year" name="publication_year"
                value="{{ old('publication_year', $book->publication_year ?? '') }}" required>
            @error('publication_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
                required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="4" required>{{ old('description', $book->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image"
                name="cover_image" accept="image/*">
            @if (isset($book) && $book->cover_image)
                <div class="mt-2">
                    <img src="{{ Storage::url($book->cover_image) }}" alt="Book Cover" class="img-thumbnail"
                        style="max-height: 200px;">
                </div>
            @endif
            @error('cover_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>
