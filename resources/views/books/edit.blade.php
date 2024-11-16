<x-books-layout header="Edit Book">
      <div class="container-fluid p-4">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card shadow-sm">
                      <div class="card-header bg-primary text-white">
                          <h3 class="card-title mb-0">Edit Book: {{ $book->title }}</h3>
                      </div>
                      <div class="card-body">
                          <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
                              @include('books.form')
                              
                              <div class="d-flex justify-content-end gap-2 mt-4">
                                  <a href="{{ route('books.index') }}" class="btn btn-secondary">
                                      <i class="fas fa-arrow-left me-2"></i>Back
                                  </a>
                                  <button type="submit" class="btn btn-primary">
                                      <i class="fas fa-save me-2"></i>Update Book
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </x-books-layout>