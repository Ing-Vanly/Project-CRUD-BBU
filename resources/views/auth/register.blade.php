@extends('layouts.app')

@section('content')
    <div class="register-container">
        <div class="register-card">
            <div class="register-image" aria-hidden="true">
                <div class="brand-overlay"></div>
                <div class="brand-content">
                    <div class="branding-top">
                        <h1>Create Account</h1>
                        <p>Join us and start your journey today</p>
                    </div>
                    <div class="illustration">
                        <svg width="200" height="200" viewBox="0 0 500 500" aria-hidden="true">
                            <circle cx="250" cy="250" r="200" fill="rgba(255,255,255,0.1)" />
                            <path
                                d="M355 170C355 226.885 309.183 273 250 273C190.817 273 145 226.885 145 170C145 113.115 190.817 67 250 67C309.183 67 355 113.115 355 170Z"
                                fill="white" fill-opacity="0.25" />
                            <path d="M145 350C145 297.533 191.533 255 250 255C308.467 255 355 297.533 355 350V400H145V350Z"
                                fill="white" fill-opacity="0.25" />
                        </svg>
                    </div>
                    <div class="branding-bottom">
                        <p>&copy; 2025 Your Company. All rights reserved.</p>
                    </div>
                </div>
            </div>
            <div class="register-form">
                <div class="form-header">
                    <h2>Create an Account</h2>
                    <p>Fill in your details to get started</p>
                </div>
                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="name" type="text" class="form-input @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                placeholder="John Doe">
                        </div>
                        @error('name')
                            <span class="error-message" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="email" type="email" class="form-input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="name@example.com" inputmode="email">
                        </div>
                        @error('email')
                            <span class="error-message" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M19 11H5C3.89543 11 3 11.8954 3 13V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V13C21 11.8954 20.1046 11 19 11Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="password" type="password" class="form-input @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <span class="error-message" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <div class="input-with-icon">
                            <span class="icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M19 11H5C3.89543 11 3 11.8954 3 13V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V13C21 11.8954 20.1046 11 19 11Z"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11"
                                        stroke="#718096" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input id="password-confirm" type="password" class="form-input" name="password_confirmation"
                                required autocomplete="new-password" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Create Account</button>
                    </div>
                    <div class="login-link">
                        <p><a href="{{ route('login') }}">Already have an account?</a></p>
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

        .register-container {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            display: block;
            inline-size: min(100%, 480px);
            overflow: hidden;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
            animation: fadeIn .6s ease-out;
            margin-inline: auto;
        }

        .register-image {
            display: none;
        }

        .register-form {
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

        .terms-policy {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin: 12px 0 20px;
        }

        .terms-policy label {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.4;
        }

        .terms-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .terms-link:hover {
            text-decoration: underline;
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

        .login-link {
            text-align: center;
            margin-top: 18px;
        }

        .login-link p {
            font-size: 14px;
            color: #4b5563;
        }

        .login-link a {
            color: #3b82f6;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }


        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .separator span {
            padding: 0 10px;
            font-size: 13px;
            color: #6b7280;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #d1d5db;
            background: none;
            color: #4b5563;
            cursor: pointer;
            transition: border-color .2s;
        }

        .social-btn:hover {
            border-color: #9ca3af;
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
            .register-form {
                width: 100%;
            }
        }

        @media (max-width:380px) {
            .register-form {
                padding: 22px;
            }
        }
    </style>
@endsection
