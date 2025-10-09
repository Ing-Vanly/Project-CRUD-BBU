@extends('layouts.app')
@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="login-form">
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Please enter your credentials to proceed</p>
                </div>
                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="email" type="email" class="form-input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="name@example.com" inputmode="email">
                        </div>
                        @error('email')
                            <span class="error-message" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="label-with-link">
                            <label for="password">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                            @endif
                        </div>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 11H5C3.89543 11 3 11.8954 3 13V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V13C21 11.8954 20.1046 11 19 11Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="password" type="password" class="form-input @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <span class="error-message" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="remember-me">
                        <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Sign in</button>
                    </div>
                    <div class="register-link">
                        <p>
                            Don't have an account?
                            <a href="{{ route('register') }}">Create one now</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .login-container {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            display: block;
            inline-size: min(100%, 440px);
            margin-inline: auto;
            overflow: hidden;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
            animation: fadeIn .6s ease-out;
        }

        .login-form {
            width: 100%;
            background: #fff;
            padding: 30px;
        }

        .form-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .form-header p {
            color: #6b7280;
            margin-top: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .label-with-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .forgot-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        .input-with-icon svg {
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
            outline: none;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        .error-message {
            display: block;
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 12px 0 20px;
        }

        .remember-me label {
            font-size: 14px;
            color: #4b5563;
        }

        .form-actions {
            margin-top: 6px;
        }

        .btn-primary {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 10px 12px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color .2s;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .register-link {
            text-align: center;
            margin-top: 18px;
        }

        .register-link p {
            font-size: 14px;
            color: #4b5563;
        }

        .register-link a {
            color: #3b82f6;
            font-weight: 500;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width:768px) {
            .login-form {
                width: 100%;
            }
        }

        @media (max-width:380px) {
            .login-form {
                padding: 22px;
            }
        }
    </style>
@endsection
