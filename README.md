Library Management System
=========================

This is a simple Library Management System built with Laravel 11, PostgreSQL, and Blade. The application allows users to manage books in their personal library, including creating, updating, deleting, and viewing books. It also includes an API for external integration.

Features
--------

*   User authentication with Laravel Breeze
    
*   CRUD operations for books
    
*   Pagination for the book list
    
*   Categories for books
    
*   File uploads for book covers
    
*   Role-based access control for managing books
    
*   RESTful API with Sanctum for token-based authentication
    

Prerequisites
-------------

Before starting, ensure you have the following installed:

*   PHP 8.2 or higher
    
*   Composer
    
*   PostgreSQL
    
*   Node.js and npm
    
*   Laravel CLI
    

Installation
------------

1.  `git clone`
    
2.  `composer install`
    
3.  `cp .env.example .env`
    
4.  `DB_CONNECTION=pgsqlDB_HOST=127.0.0.1DB_PORT=5432DB_DATABASE=your_database_nameDB_USERNAME=your_database_userDB_PASSWORD=your_database_password`
    
5.  `php artisan key:generate`
    
6.  `php artisan migrate --seed`
    
7.  `npm run build`
    
8.  `php artisan serve`
    
9.  Visit the application in your browser at http://localhost:8000.
    

Usage
-----

### 1\. Authentication

#### Logging In

To log in and obtain an authentication token:

1.  Register a new user by navigating to /register in your browser.
    
2.  **Request:**`POST /api/sanctum/token`
    
**Request Body (JSON):**`json { "email": "user@example.com", "password": "your_password", "name": "user_name"}`
    
**Response:**`json { "access_token": "your_api_token"}`
    
Use the generated token for authenticated API requests by including it in the Authorization header:`Authorization: Bearer your_api_token`
    

### 2\. Managing Books

*   Navigate to the "Books List" from the dashboard.
    
*   Create, update, delete, or view books.
    

### 3\. API Endpoints

The application provides RESTful API endpoints secured with Sanctum:

*   GET /api/books - List all books
    
*   GET /api/books/{id} - Get details of a specific book
    
*   POST /api/books - Create a new book
    
*   PUT /api/books/{id} - Update a book
    
*   DELETE /api/books/{id} - Delete a book
    

To use the API:

1.  Obtain an API token as described above.
    
2.  Add the token to your requests' Authorization header:Authorization: Bearer
    

Project Structure
-----------------

*   app/Http/Controllers: Contains the controllers for handling web and API routes.
    
*   app/Models: Includes the Book and Category models.
    
*   resources/views: Contains the Blade templates for the user interface.
    
*   routes/web.php: Defines web routes.
    
*   routes/api.php: Defines API routes.
    
*   database/migrations: Contains database migration files.
    
*   database/seeders: Includes seeders to populate the database with sample data.
    

Components
----------

This project uses a custom component for book management pages:

*   BooksLayout: A Blade component that wraps the layouts.app layout for book-related views.
    

Testing
-------

Run feature and unit tests with the following command:

`php artisan test`

Troubleshooting
---------------

1.  **Unable to locate component or layout**:
    
    *   Ensure resources/views/layouts/app.blade.php exists.
        
    *   Ensure your Blade components are correctly registered.
        
2.  **Database connection issues**:
    
    *   Verify your .env configuration.
        
    *   Ensure PostgreSQL is running.
        
3.  **Assets not loading**:
    
    *   Rebuild assets with `npm run build`.
