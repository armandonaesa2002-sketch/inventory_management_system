<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
</head>

<body>

    <h2>Password Reset Request</h2>

    <p>Hello!</p>

    <p>
        We received a request to reset your password.
    </p>

    <p>
        Click the button below to reset your password.
    </p>

    <a href="{{ $resetLink }}"
        style="background-color: #007bff;
              color: white;
              padding: 12px 20px;
              text-decoration: none;">
        Reset Password
    </a>

    <p>
        If you did not request a password reset,
        please ignore this email.
    </p>

</body>

</html>