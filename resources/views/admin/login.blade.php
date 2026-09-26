<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/Trendysongz-favicon.ico') }}" type="image/x-icon">
    <title>Admin login | TrendySongz</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 20px; font-family: Arial, Helvetica, sans-serif; background: #f3f5ee; color: #222; }
        .login-card { width: min(100%, 420px); padding: 35px; border: 1px solid #e1e6d7; border-radius: 14px; background: white; box-shadow: 0 14px 42px #26331312; }
        h1 { margin: 0 0 5px; font-size: 24px; text-align: center; } p { margin: 0 0 25px; color: #66705e; text-align: center;}
        label { display: grid; gap: 7px; margin: 0 0 17px; font-weight: 700; }
        input { width: 100%; padding: 12px; border: 1px solid #b9c4ae; border-radius: 7px; font: inherit; }
        button { width: 100%; padding: 12px; border: 0; border-radius: 7px; background: #63712a; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .error { color: #a8322b; margin-bottom: 14px; }

        .login-logo {
            display: block;
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <main class="login-card">
    <img
        class="login-logo"
        src="{{ asset('images/Trendysongz-favicon.ico') }}"
        alt="TrendySongz logo"
    >

    <h1>TrendySongz Admin</h1>
    <p>Sign in to manage your content.</p>

    @if ($errors->any())
        <div class="error" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
        </label>

        <label>
            Password
                <input type="password" name="password" autocomplete="current-password" required>
            </label>

            <button type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
