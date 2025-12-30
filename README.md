# Laravel 12 Passport OAuth2 Authentication System

<div align="center">
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/Passport-OAuth2-4FC08D?style=for-the-badge&logo=passport&logoColor=white" alt="Passport OAuth2">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
    <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.0">
    <img src="https://img.shields.io/badge/Blade-FB503B?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Blade">
    <img src="https://img.shields.io/badge/Eloquent-ORM-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Eloquent ORM">

</div>

A production-ready Laravel 12 authentication system using Laravel Passport for OAuth2 implementation with role-based access control, modern UI, and comprehensive API documentation.

# 🎯 Overview

This project is a complete, production-ready authentication system built with Laravel 12 and Laravel Passport. It implements OAuth2 authentication with personal access tokens, role-based access control (Admin/User), and provides both RESTful API endpoints and a modern web interface.

### What Makes This Special?

-   Modern Laravel 12 with latest features
-   Full OAuth2 Implementation using Laravel Passport
-   Role-Based Access Control (RBAC) with Admin and User roles
-   Interactive Web Interface with pure CSS (no JavaScript dependencies)
-   Comprehensive Error Handling with try-catch blocks
-   Production-Ready Code with best practices
-   Complete API Documentation with Postman collection
-   Security First approach with token revocation and validation

# Role-Based Access Control

-   👨‍💼 Admin Role with full system access
-   👤 User Role with limited permissions
-   🛡️ Protected Routes based on user roles
-   📊 Admin Dashboard for user management
-   ✅ Permission System for granular access control

# API Features

-   🌐 RESTful API with JSON responses
-   📱 Token-Based Authentication using Bearer tokens
-   🔒 Secure Endpoints with auth:api middleware
-   📖 Comprehensive Documentation with examples
-   ⚡ Fast Response Times with optimized queries

# Web Interface

-   🎨 Modern UI Design with gradient themes
-   🔄 Toggle Login/Register forms on single page
-   📱 Fully Responsive for all devices
-   🎯 User-Friendly with clear error messages
-   ⚡ No JavaScript Required for core functionality
-   🎭 Role-Based UI showing different content per role

# Developer Experience

-   🐛 Comprehensive Error Handling with try-catch blocks
-   📝 Clean Code following Laravel best practices
-   🔍 Easy Debugging with detailed error messages
-   📚 Well-Documented code with comments
-   🧪 Testable architecture

# 🛠️ Step-by-Step Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/Ami-Shahadat-Hossain/laravel-passport-auth.git
cd laravel-passport-auth
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

```bash
# Update the database configuration in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_passport_auth
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Install Passport (Modern Approach)

```bash
# This single command does everything:
# - Installs Passport
# - Runs migrations
# - Creates encryption keys
# - Creates OAuth clients
php artisan install:api --passport
```

### Step 6: Create Admin Middleware

`php artisan make:middleware AdminMiddleware`

```PHP

// app/Http/Middleware/AdminMiddleware.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
```

`Register middleware in bootstrap/app.php`

```PHP
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Set Permissions (For Linux)

```bach
bashchmod -R 775 storage bootstrap/cache
chmod 600 storage/oauth-private.key
chmod 644 storage/oauth-public.key
```

### Step 8: Start Development Server

```bash
php artisan serve
```

Visit: http://127.0.0.1:8000

# ⚙️ Configuration

Passport Configuration

```PHP
# Edit app/Providers/AppServiceProvider.php:
public function boot(): void
{
    // Token expiration settings
    Passport::tokensExpireIn(now()->addDays(15));
    Passport::refreshTokensExpireIn(now()->addDays(30));
    Passport::personalAccessTokensExpireIn(now()->addMonths(6));
}
```

# Authentication Guard

```php
# Verify config/auth.php:
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'passport',  // Uses Passport for API
        'provider' => 'users',
    ],
],
```

### Passport Tables

Automatically created by `php artisan passport:install`:

| Table                           | Purpose                           | Badge                                                                                                                                                             |
| ------------------------------- | --------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `oauth_clients`                 | OAuth client applications         | <img src="https://img.shields.io/badge/oauth_clients-4FC08D?style=flat-square&logo=passport&logoColor=white" alt="oauth_clients">                                 |
| `oauth_access_tokens`           | Active access tokens              | <img src="https://img.shields.io/badge/oauth_access_tokens-4FC08D?style=flat-square&logo=passport&logoColor=white" alt="oauth_access_tokens">                     |
| `oauth_refresh_tokens`          | Refresh tokens for token renewal  | <img src="https://img.shields.io/badge/oauth_refresh_tokens-4FC08D?style=flat-square&logo=passport&logoColor=white" alt="oauth_refresh_tokens">                   |
| `oauth_auth_codes`              | Authorization codes (OAuth2 flow) | <img src="https://img.shields.io/badge/oauth_auth_codes-4FC08D?style=flat-square&logo=passport&logoColor=white" alt="oauth_auth_codes">                           |
| `oauth_personal_access_clients` | Personal access client references | <img src="https://img.shields.io/badge/oauth_personal_access_clients-4FC08D?style=flat-square&logo=passport&logoColor=white" alt="oauth_personal_access_clients"> |

# 📡 API Documentation

## Test 1: User Registration

### Endpoint

```
POST http://127.0.0.1:8000/api/register
```

### Headers

```
Content-Type: application/json
Accept: application/json
```

### Body (JSON)

**Test Case 1: Register as Regular User**

```json
{
    "name": "Ami Shahadat",
    "email": "shahadat@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user"
}
```

**Test Case 2: Register as Admin**

```json
{
    "name": "Admin User",
    "email": "admin@example.com",
    "password": "admin12345",
    "password_confirmation": "admin12345",
    "role": "admin"
}
```

### Expected Response (201 Created)

```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "Ami Shahadat",
            "email": "shahadat@example.com",
            "role": "user",
            "created_at": "2024-12-27T10:00:00.000000Z",
            "updated_at": "2024-12-27T10:00:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "Bearer"
    }
}
```

### Save the Access Token

After successful registration, copy the `access_token` value. You'll need it for protected endpoints.
**Example Token:**

```
eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWJj...
```

## Test 2: User Login

### Endpoint

```
POST http://127.0.0.1:8000/api/login
```

### Headers

```
Content-Type: application/json
Accept: application/json
```

### Body (JSON)

```json
{
    "email": "shahadat@example.com",
    "password": "password123"
}
```

### Expected Response (200 OK)

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Ami Shahadat",
            "email": "shahadat@example.com",
            "role": "user",
            "created_at": "2024-12-27T10:00:00.000000Z",
            "updated_at": "2024-12-27T10:00:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "Bearer"
    }
}
```

## Test 3: Get Authenticated User

### Endpoint

```
GET http://127.0.0.1:8000/api/user
```

### Headers

```
Authorization: Bearer YOUR_ACCESS_TOKEN_HERE
Accept: application/json
```

**Important:** Replace `YOUR_ACCESS_TOKEN_HERE` with the actual token from login/register response.

### Expected Response (200 OK)

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Ami Shahadat",
        "email": "shahadat@example.com",
        "role": "user",
        "email_verified_at": null,
        "created_at": "2024-12-27T10:00:00.000000Z",
        "updated_at": "2024-12-27T10:00:00.000000Z"
    }
}
```

## Test 4: Get All Users (Admin Only)

### Endpoint

```
GET http://127.0.0.1:8000/api/users
```

### Headers

```
Authorization: Bearer ADMIN_ACCESS_TOKEN_HERE
Accept: application/json
```

**Note:** You must use an admin user's token for this endpoint.

### Expected Response (200 OK)

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Ami Shahadat",
            "email": "shahadat@example.com",
            "role": "user",
            "created_at": "2024-12-27T10:00:00.000000Z",
            "updated_at": "2024-12-27T10:00:00.000000Z"
        },
        {
            "id": 2,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin",
            "created_at": "2024-12-27T10:01:00.000000Z",
            "updated_at": "2024-12-27T10:01:00.000000Z"
        }
    ]
}
```

### Expected Response with User Token (403 Forbidden)

```json
{
    "success": false,
    "message": "Unauthorized. Admin access required."
}
```

## Test 5: Get Specific User

### Endpoint

```

GET http://127.0.0.1:8000/api/users/{id}

```

**Example:**

```

GET http://127.0.0.1:8000/api/users/1

```

### Headers

```

Authorization: Bearer YOUR_ACCESS_TOKEN_HERE
Accept: application/json

```

### Expected Response (200 OK)

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Ami Shahadat",
        "email": "shahadat@example.com",
        "role": "user",
        "created_at": "2024-12-27T10:00:00.000000Z",
        "updated_at": "2024-12-27T10:00:00.000000Z"
    }
}
```

## Test 6: Logout

### Endpoint

```
POST http://127.0.0.1:8000/api/logout
```

### Headers

```
Authorization: Bearer YOUR_ACCESS_TOKEN_HERE
Accept: application/json
```

### Expected Response (200 OK)

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

Effect:

-   Revokes the access token
-   Deletes associated refresh tokens
-   oken becomes invalid immediately

## Understanding OAuth2 Scopes

### User Scopes

Regular users get the following scope:

-   `user-read`: Can read their own user information

### Admin Scopes

Admin users get all scopes:

-   `admin-access`: Full administrative access
-   `user-read`: Can read user information
-   `user-write`: Can modify user information

### Testing Scope-Based Access

1. **Create a Regular User** → Login → Note the token
2. **Create an Admin User** → Login → Note the token
3. **Test `/api/users` with user token** → Should get 403
4. **Test `/api/users` with admin token** → Should get 200

## Common Error Responses

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

**Solution:** Add valid Bearer token to Authorization header

### 403 Forbidden

```json
{
    "success": false,
    "message": "Unauthorized. Admin access required."
}
```

**Solution:** Use admin user token

### 422 Validation Error

```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

**Solution:** Fix the input data according to validation rules

### 500 Server Error

```json
{
    "success": false,
    "message": "Registration failed",
    "error": "Detailed error message here"
}
```

## Troubleshooting

### Token Not Working

```bash
# Clear all tokens and regenerate
php artisan passport:keys --force
php artisan passport:client --personal
```

### Database Issues

```bash
# Reset database and migrations
php artisan migrate:fresh
php artisan passport:install
```

## Summary

You now have a complete testing guide for Laravel Passport OAuth2 authentication. Test all endpoints systematically, and verify that:

-   ✅ Registration works for both user and admin roles
-   ✅ Login generates valid tokens
-   ✅ Protected endpoints require authentication
-   ✅ Admin-only endpoints enforce role-based access
-   ✅ Logout properly revokes tokens
-   ✅ Error handling works correctly

# 🏗️ Architecture

```PHP
laravel-passport-auth/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php        # API authentication
│   │   │   │   └── UserController.php        # API user management
│   │   │   └── Web/
│   │   │       ├── AuthController.php        # Web authentication (views)
│   │   │       └── DashboardController.php   # Web dashboard
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   ├── Models/
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   ├── auth.php                               # Authentication configuration
│   ├── database.php
│   ├── passport.php                           # Passport configuration
├── database/
│   └── migrations/
│   │   ├── xxxx_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_reset_tokens_table.php
│   │   ├── 2016_06_01_000001_create_oauth_auth_codes_table.php
│   │   ├── 2016_06_01_000002_create_oauth_access_tokens_table.php
│   │   ├── 2016_06_01_000003_create_oauth_refresh_tokens_table.php
│   │   ├── 2016_06_01_000004_create_oauth_clients_table.php
│   │   ├── 2016_06_01_000005_create_oauth_personal_access_clients_table.php
│   │   └── xxxx_add_role_to_users_table.php
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   └── users.blade.php
│       └── dashboard.blade.php
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   ├── oauth-private.key
│   └── oauth-public.key
└── .env
```
