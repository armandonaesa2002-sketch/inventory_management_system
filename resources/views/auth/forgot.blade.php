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
                <h1>Manage your assets in one place.</h1>
                <p>Keep track of your inventory with a clear, organized workspace.</p>
            </div>
        </section>

        <section class="form-panel">
            <form class="login-form" action="{{route('password.email')}}" method="post">
                @csrf
                <h2>Forgot Password</h2>
                <p class="intro">type email to reset</p>
                @if(session('send-success'))
                <div class="alert alert-success">
                    <p>{{session('send-success')}}</p>
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div class="field">
                    <label for="username">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter Email"
                        required
                        autofocus>

                </div>
                <button class="sign-in" type="submit">Send Link</button>
                <a href="{{url('/login')}}" class="back">Back</a>
            </form>
        </section>
    </main>
</body>

</html>