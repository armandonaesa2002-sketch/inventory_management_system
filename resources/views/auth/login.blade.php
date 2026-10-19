<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <main class="login-page">
        <section class="brand-panel" aria-label="Inventory system introduction">
            <div class="brand">
                <span class="brand-mark" aria-hidden="true">▦</span>
                <span>INVENTORY</span>
            </div>

            <div class="art" aria-hidden="true">
                <div class="box box-one"></div>
                <div class="box box-two"></div>
                <div class="box box-three"></div>
            </div>

            <div class="brand-copy">
                <h1>Manage your assets in one place.</h1>
                <p>Keep track of your inventory with a clear, organized workspace.</p>
            </div>
        </section>

        <section class="form-panel">
            <form class="login-form" action="{{ url('/login')}}" method="post">
                @csrf
                <h2>Welcome back</h2>
                <p class="intro">Sign in to your inventory account</p>
                @error('login')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
                @enderror
                <div class="field">
                    <label for="username">Email / Name</label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        value="{{ old('login') }}"
                        placeholder="Enter Email or name"
                        required
                        autofocus>

                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>
                        <button class="toggle-password" type="button" id="togglePassword">
                            Show
                        </button>
                    </div>
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <button class="sign-in" type="submit">Sign In</button>

                <p class="help">Need help? Contact your administrator</p>
            </form>
        </section>
    </main>

    <script>
        const passwordInput = document.getElementById("password");
        const toggleButton = document.getElementById("togglePassword");

        toggleButton.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            toggleButton.textContent = isHidden ? "Hide" : "Show";
        });
    </script>
</body>

</html>