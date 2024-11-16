<x-books-layout header="Add New Book">
      <div class="container-fluid p-4">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card shadow-sm">
                      <div class="card-header bg-primary text-white">
                          <h3 class="card-title mb-0">Create New Book</h3>
                      </div>
                      <div class="card-body">
                          <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @include('books.form')
                              
                              <div class="d-flex justify-content-end gap-2 mt-4">
                                  <a href="{{ route('books.index') }}" class="btn btn-secondary">
                                      <i class="fas fa-arrow-left me-2"></i>Back
                                  </a>
                                  <button type="submit" class="btn btn-primary">
                                      <i class="fas fa-save me-2"></i>Save Book
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </x-books-layout>