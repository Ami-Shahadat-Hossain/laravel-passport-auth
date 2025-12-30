<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Laravel Passport Auth</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .logout-form {
            display: inline;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-logout:hover {
            background: white;
            color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e1e8ed;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-user {
            background: #e3f2fd;
            color: #1976d2;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-size: 13px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .feature-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .feature-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            padding: 10px 0;
            padding-left: 25px;
            position: relative;
            color: #555;
        }

        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .navbar-menu {
                flex-direction: column;
                width: 100%;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-brand">Laravel Passport Auth</div>
        <div class="navbar-menu">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            @if ($user->isAdmin())
                <a href="{{ route('admin.users') }}" class="nav-link">All Users</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Welcome, {{ $user->name }}!</h1>
                <span class="badge {{ $user->isAdmin() ? 'badge-admin' : 'badge-user' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">User ID</div>
                    <div class="info-value">#{{ $user->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Role</div>
                    <div class="info-value">{{ ucfirst($user->role) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="feature-box">
                <h3 class="feature-title">Available Features</h3>
                <ul class="feature-list">
                    <li>Secure OAuth2 authentication with Laravel Passport</li>
                    <li>Personal access tokens with custom scopes</li>
                    <li>Protected API endpoints with role-based access</li>
                    @if ($user->isAdmin())
                        <li><strong>Admin Access:</strong> Manage all users and system settings</li>
                        <li><strong>Admin Scopes:</strong> Full administrative privileges</li>
                    @else
                        <li>User-level access to personal resources</li>
                    @endif
                    <li>Comprehensive API documentation for testing</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">API Testing Information</h2>
            <div class="info-item" style="margin-top: 20px;">
                <div class="info-label">Your Role Capabilities</div>
                <div class="info-value" style="font-size: 15px; line-height: 1.6; color: #555;">
                    @if ($user->isAdmin())
                        As an <strong>Admin</strong>, you have access to:
                        <ul style="margin-top: 10px; padding-left: 20px;">
                            <li>All API endpoints including user management</li>
                            <li>Scopes: <code>admin-access</code>, <code>user-read</code>, <code>user-write</code></li>
                            <li>Full CRUD operations on user resources</li>
                        </ul>
                    @else
                        As a <strong>User</strong>, you have access to:
                        <ul style="margin-top: 10px; padding-left: 20px;">
                            <li>Your own profile and resources</li>
                            <li>Scopes: <code>user-read</code></li>
                            <li>Limited access to sensitive operations</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
