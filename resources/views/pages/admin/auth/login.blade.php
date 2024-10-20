<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afandina | Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #000000;
        }

        .container {
            position: relative;
            width: 256px;
            height: 256px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container span {
            position: absolute;
            left: 0;
            width: 32px;
            height: 6px;
            background: #111111;
            border-radius: 8px;
            transform-origin: 128px;
            transform: scale(2.2) rotate(calc(var(--i) * (360deg / 50)));
            animation: animateBlink 3s linear infinite;
            animation-delay: calc(var(--i) * (3s / 50));
        }

        @keyframes animateBlink {
            0% { background: #d9ad04; }
            25% { background: #2c4766; }
        }

        .login-box {
            position: absolute;
            width: 90%; /* Adjusted to be responsive */
            max-width: 400px;
        }

        h2 {
            font-size: 2em;
            color: #d9ad04;
            text-align: center;
        }

        .input-box {
            position: relative;
            margin: 25px 0;
        }

        .input-box input {
            width: 100%;
            height: 50px;
            background: transparent;
            border: 2px solid #2c4766;
            outline: none;
            border-radius: 40px;
            font-size: 1em;
            color: #fff;
            padding: 0 20px;
            transition: .5s ease;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: #d9ad04;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            font-size: 1em;
            color: #ffffff;
            pointer-events: none;
            transition: .5s ease;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: 1px;
            font-size: .8em;
            background: #1f293a;
            padding: 0 6px;
            color: #d9ad04;
        }

        .forgot-pass {
            margin: -15px 0 10px;
            text-align: center;
        }

        .forgot-pass a {
            font-size: .85em;
            background-color: transparent;
            text-decoration: none;
        }

        .forgot-pass a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            height: 45px;
            background: #d9ad04;
            border: none;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1em;
            color: #1f293a;
            font-weight: 600;
        }

        .signup-link {
            margin: 20px 0 10px;
            text-align: center;
        }

        .signup-link a {
            font-size: 1em;
            color: #d9ad04;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .container {
                width: 200px;
                height: 200px;
            }

            .login-box {
                width: 100%;
                padding: 0 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            .input-box input {
                height: 45px;
            }

            .btn {
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 160px;
                height: 160px;
            }

            .login-box {
                width: 100%;
                padding: 0 10px;
            }

            h2 {
                font-size: 1.2em;
            }

            .input-box input {
                height: 40px;
            }

            .btn {
                height: 38px;
                font-size: 0.9em;
            }

            .signup-link a {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-box">
        <div class="login-logo" style="text-align: center">
            <a href="#">
                <img src="{{ asset('/admin/dist/logo/afandina_3.png') }}" alt="Logo" style="width: 250px;">
            </a>
        </div>
        <form action="{{route('admin.login')}}" method="post">
            @method('post')
            @csrf
            <div class="input-box">
                <input name="email" type="email" required>
                <label>Email</label>
            </div>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="input-box">
                <input name="password" type="password" required>
                <label>Password</label>
            </div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="forgot-pass">
                {{-- <a href="#">Forgot your password?</a> --}}
            </div>

            <button class="btn" type="submit" style="margin-top: 5px">Login</button>

            <div class="signup-link">
                {{-- <a href="#">Sign up</a> --}}
            </div>
        </form>
    </div>

    <span style="--i:0;"></span>
    <span style="--i:1;"></span>
    <span style="--i:2;"></span>
    <span style="--i:0;"></span>
    <span style="--i:1;"></span>
    <span style="--i:2;"></span>
    <span style="--i:3;"></span>
    <span style="--i:4;"></span>
    <span style="--i:5;"></span>
    <span style="--i:6;"></span>
    <span style="--i:7;"></span>
    <span style="--i:8;"></span>
    <span style="--i:9;"></span>
    <span style="--i:10;"></span>
    <span style="--i:11;"></span>
    <span style="--i:12;"></span>
    <span style="--i:13;"></span>
    <span style="--i:14;"></span>
    <span style="--i:15;"></span>
    <span style="--i:16;"></span>
    <span style="--i:17;"></span>
    <span style="--i:18;"></span>
    <span style="--i:19;"></span>
    <span style="--i:20;"></span>
    <span style="--i:21;"></span>
    <span style="--i:22;"></span>
    <span style="--i:23;"></span>
    <span style="--i:24;"></span>
    <span style="--i:25;"></span>
    <span style="--i:26;"></span>
    <span style="--i:27;"></span>
    <span style="--i:28;"></span>
    <span style="--i:29;"></span>
    <span style="--i:30;"></span>
    <span style="--i:31;"></span>
    <span style="--i:32;"></span>
    <span style="--i:33;"></span>
    <span style="--i:34;"></span>
    <span style="--i:35;"></span>
    <span style="--i:36;"></span>
    <span style="--i:37;"></span>
    <span style="--i:38;"></span>
    <span style="--i:39;"></span>
    <span style="--i:40;"></span>
    <span style="--i:41;"></span>
    <span style="--i:42;"></span>
    <span style="--i:43;"></span>
    <span style="--i:44;"></span>
    <span style="--i:45;"></span>
    <span style="--i:46;"></span>
    <span style="--i:47;"></span>
    <span style="--i:48;"></span>
    <span style="--i:49;"></span>
</div>
</body>
</html>
