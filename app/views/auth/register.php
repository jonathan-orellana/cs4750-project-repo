<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h2>Register</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/register">
        <div>
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Register</button>
    </form>

    <p><a href="/login">Already have an account?</a></p>
</body>
</html>
