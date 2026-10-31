<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
                <h1></h1>
                <p></p>
            </div>
        </section>

        <section class="form-panel">
            <form class="login-form" action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <h2>Reset Password</h2>
                <p class="intro">Reset your password</p>
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        placeholder="Enter Email"
                        required
                        autofocus>

                </div>
                <div class="field">
                    <div class="password-wrapper">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="New Password"
                            required>
                        <button type="button" class="toggle-password"
                            onclick="togglePassword('password', this)"
                            aria-label="Show password">
                            show
                        </button>
                    </div>

                </div>
                <div class="field">
                    <div class="password-wrapper">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="Confirm Password"
                            required>
                        <button type="button" class="toggle-password"
                            onclick="togglePassword('password_confirmation', this)"
                            aria-label="Show password">
                            show
                        </button>
                    </div>

                </div>
                <button class="sign-in" type="submit">Reset Password</button>
            </form>
        </section>
    </main>
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            if (input.type === "password") {
                input.type = "text";
                button.textContent = "hide";
                button.setAttribute("aria-label", "Hide password");
            } else {
                input.type = "password";
                button.textContent = "show";
                button.setAttribute("aria-label", "Show password");
            }
        }
    </script>
</body>

</html>