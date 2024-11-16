<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Library Management System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <i class="fas fa-book-open text-primary fa-2x"></i>
                            <span class="ml-2 text-xl font-bold">LibraryMS</span>
                        </div>
                        
                        @if (Route::has('login'))
                            <div class="flex items-center">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            Welcome to Library Management System
                        </h1>
                        <p class="text-xl text-gray-600 dark:text-gray-400">
                            Your digital solution for efficient library management
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="text-primary mb-4">
                                <i class="fas fa-book fa-3x"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                Book Management
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Easily manage your book collection with our comprehensive cataloging system
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="text-primary mb-4">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                Member Management
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Track member activities and manage memberships efficiently
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="text-primary mb-4">
                                <i class="fas fa-exchange-alt fa-3x"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                Lending System
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Streamline book borrowing and returns with our automated system
                            </p>
                        </div>
                    </div>

                    <!-- Stats Section -->
                    <div class="mt-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">1000+</div>
                                <div class="text-gray-600 dark:text-gray-400">Books Available</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">500+</div>
                                <div class="text-gray-600 dark:text-gray-400">Active Members</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">24/7</div>
                                <div class="text-gray-600 dark:text-gray-400">Digital Access</div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-12 text-center">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            Ready to get started?
                        </h2>
                        <div class="space-x-4">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                Sign Up Now
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-lg">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 shadow mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-gray-600 dark:text-gray-400">
                        <p>&copy; {{ date('Y') }} Library Management System. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>