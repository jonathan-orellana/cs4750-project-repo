<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/login.css">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-brand" aria-label="Travel Cost Planner"></section>

        <section class="login-panel" aria-labelledby="login-heading">
            <form class="login-form" method="POST" action="/login">
                <h1 id="login-heading">Login</h1>

                <?php if (!empty($error)): ?>
                    <p class="form-error">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <label>
                    <span>Email</span>
                    <input type="email" name="email" placeholder="Email" required>
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" placeholder="Password" required>
                </label>

                <button type="submit">Login</button>

                <p class="signup-copy">
                    Don't have an account?
                    <a href="/register">Sign Up</a>
                </p>
            </form>
        </section>
    </main>
</body>
</html>
