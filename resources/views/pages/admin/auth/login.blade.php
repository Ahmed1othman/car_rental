@extends('layouts.login_layout')

@section('content')

        <!-- Logo Section -->
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('/admin/dist/logo/afandina_3.png') }}" alt="Car Rental Logo" style="width: 250px;">
            </a>
        </div>

        <!-- Card Section -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Afandi</b>Na</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to book your next car!</p>

                <!-- Login Form -->
                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="/">Forgot Password?</a>
                </p>
            </div>
        </div>

    <!-- Full-Screen Video Background with Overlay -->
    <style>
        /* Make sure the body and html take the full height of the viewport */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }


        /* Styling for the card and login box */
        .login-box {
            z-index: 1;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.85);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .login-logo img {
            margin-bottom: 20px;
        }
    </style>


@endsection
