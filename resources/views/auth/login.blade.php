<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login / Register - Laravel Passport Auth</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url("https://images.wallpapersden.com/image/download/laravel-php-4k_bGhuZ2mUmZqaraWkpJRpZWVlrWdnamU.jpg") center / cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .container {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .form-container {
            position: relative;
            flex: 1;
        }

        .form-wrapper {
            padding: 20px 24px;
            transition: opacity 0.3s ease;
        }

        .form-wrapper.hidden {
            display: none;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .header h1 {
            color: #333;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header p {
            color: #666;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e1e8ed;
            border-radius: 6px;
            font-size: 15px;
            /* Increased font size */
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        /* Hover effect for input fields */
        .form-group input:hover,
        .form-group select:hover {
            border-color: #b0b7ff;
            background: #f0f2ff;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        /* Enhanced focus effect with animation */
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
            transform: translateY(-1px);
            font-size: 16px;
            /* Bigger font size when focused */
            padding: 11px 13px;
            /* Slightly larger padding for bigger text */
        }

        /* Smooth transition for font size change */
        .form-group input,
        .form-group select {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-group input.error,
        .form-group select.error {
            border-color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 11px;
            margin-top: 3px;
            display: block;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .toggle-form {
            text-align: center;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #e1e8ed;
        }

        .toggle-form p {
            color: #666;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .toggle-btn:hover {
            color: #764ba2;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-label {
            display: block;
            padding: 10px;
            border: 1.5px solid #e1e8ed;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            font-weight: 500;
            color: #666;
            font-size: 12px;
        }

        .role-option input[type="radio"]:checked+.role-label {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .role-label:hover {
            border-color: #667eea;
            background: #f0f2ff;
            transform: translateY(-1px);
        }

        .info-box {
            background: #e7f3ff;
            border-left: 3px solid #2196F3;
            padding: 8px 10px;
            border-radius: 5px;
            margin-bottom: 14px;
            font-size: 11px;
            color: #0c5460;
            line-height: 1.4;
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
                align-items: flex-start;
                min-height: 100vh;
            }

            .container {
                border-radius: 12px;
                margin-top: 5px;
                margin-bottom: 5px;
            }

            .form-wrapper {
                padding: 16px 18px;
            }

            .header h1 {
                font-size: 20px;
                margin-bottom: 4px;
            }

            .header p {
                font-size: 12px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            .form-group label {
                font-size: 11px;
                margin-bottom: 4px;
            }

            .form-group input,
            .form-group select {
                padding: 8px 10px;
                font-size: 14px;
                /* Still larger on mobile */
            }

            /* Mobile focus effects */
            .form-group input:focus,
            .form-group select:focus {
                font-size: 15px;
                /* Slightly smaller increase on mobile */
                padding: 9px 11px;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            }

            .btn {
                padding: 9px;
                font-size: 12px;
                margin-top: 4px;
            }

            .info-box {
                font-size: 10px;
                padding: 6px 8px;
                margin-bottom: 12px;
            }

            .role-selector {
                gap: 8px;
                margin-bottom: 12px;
            }

            .role-label {
                padding: 8px;
                font-size: 11px;
            }

            .toggle-form {
                margin-top: 12px;
                padding-top: 12px;
            }

            .toggle-form p {
                font-size: 11px;
            }

            .toggle-btn {
                font-size: 11px;
            }
        }

        /* For very small screens */
        @media (max-height: 700px) and (max-width: 480px) {
            body {
                padding: 5px;
            }

            .container {
                margin-top: 2px;
                margin-bottom: 2px;
            }

            .form-wrapper {
                padding: 12px 14px;
            }

            .header {
                margin-bottom: 12px;
            }

            .header h1 {
                font-size: 18px;
                margin-bottom: 3px;
            }

            .header p {
                font-size: 11px;
            }

            .form-group {
                margin-bottom: 8px;
            }

            .form-group label {
                font-size: 10px;
                margin-bottom: 3px;
            }

            .form-group input,
            .form-group select {
                padding: 7px 9px;
                font-size: 13px;
            }

            .form-group input:focus,
            .form-group select:focus {
                font-size: 14px;
                padding: 8px 10px;
            }

            .btn {
                padding: 8px;
                font-size: 11px;
            }

            .info-box {
                padding: 5px 7px;
                font-size: 9px;
                margin-bottom: 10px;
            }

            .role-selector {
                gap: 6px;
                margin-bottom: 10px;
            }

            .role-label {
                padding: 7px;
                font-size: 10px;
            }

            .toggle-form {
                margin-top: 10px;
                padding-top: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <!-- Login Form -->
            <div class="form-wrapper" id="loginForm">
                <div class="header">
                    <h1>Welcome Back</h1>
                    <p>Login to your account</p>
                </div>

                @if (session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any() && !old('_form'))
                    <div class="error-message"
                        style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 10px; border-radius: 5px; margin-bottom: 14px; font-size: 12px;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_form" value="login">

                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" id="login-email" name="email" value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'error' : '' }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password"
                            class="{{ $errors->has('password') ? 'error' : '' }}" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="toggle-form">
                    <p>Don't have an account?</p>
                    <button type="button" class="toggle-btn" onclick="toggleForms()">Create Account</button>
                </div>
            </div>

            <!-- Register Form -->
            <div class="form-wrapper hidden" id="registerForm">
                <div class="header">
                    <h1>Create Account</h1>
                    <p>Join us today</p>
                </div>

                <div class="info-box">
                    <strong>Note:</strong> Select your role carefully. Admin users have full access to manage all users.
                </div>

                @if ($errors->any() && old('_form') === 'register')
                    <div class="error-message"
                        style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 10px; border-radius: 5px; margin-bottom: 14px; font-size: 12px;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_form" value="register">
                    <div class="form-group">
                        <label>Select Role</label>
                        <div class="role-selector">
                            <div class="role-option">
                                <input type="radio" id="role-user" name="role" value="user"
                                    {{ old('role', 'user') === 'user' ? 'checked' : '' }} required>
                                <label for="role-user" class="role-label">User</label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="role-admin" name="role" value="admin"
                                    {{ old('role') === 'admin' ? 'checked' : '' }}>
                                <label for="role-admin" class="role-label">Admin</label>
                            </div>
                        </div>
                        @error('role')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" id="register-name" name="name" value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'error' : '' }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register-email">Email Address</label>
                        <input type="email" id="register-email" name="email" value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'error' : '' }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <input type="password" id="register-password" name="password"
                            class="{{ $errors->has('password') ? 'error' : '' }}" required minlength="8">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register-password-confirm">Confirm Password</label>
                        <input type="password" id="register-password-confirm" name="password_confirmation" required
                            minlength="8">
                    </div>

                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>

                <div class="toggle-form">
                    <p>Already have an account?</p>
                    <button type="button" class="toggle-btn" onclick="toggleForms()">Login</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            loginForm.classList.toggle('hidden');
            registerForm.classList.toggle('hidden');
        }

        // Show register form if there are registration errors
        @if ($errors->any() && old('_form') === 'register')
            document.addEventListener('DOMContentLoaded', function() {
                toggleForms();
            });
        @endif
    </script>
</body>

</html>
