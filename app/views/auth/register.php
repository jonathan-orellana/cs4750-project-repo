<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/register.css">
</head>
<body class="register-page">
    <main class="register-shell">
        <section class="register-brand" aria-label="Travel Cost Planner"></section>

        <section class="register-panel" aria-labelledby="register-heading">
            <form class="register-form" method="POST" action="/register">
                <h1 id="register-heading">Sign Up</h1>

                <?php if (!empty($error)): ?>
                    <p class="form-error">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <div class="name-row">
                    <label>
                        <span>First name</span>
                        <input
                            type="text"
                            name="first_name"
                            placeholder="First name"
                            value="<?= htmlspecialchars($formData['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                    </label>

                    <label>
                        <span>Last name</span>
                        <input
                            type="text"
                            name="last_name"
                            placeholder="Last name"
                            value="<?= htmlspecialchars($formData['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                    </label>
                </div>

                <label>
                    <span>Email</span>
                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" placeholder="Password" required>
                </label>

                <label>
                    <span>Confirm Password</span>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </label>

                <button type="submit">Sign Up</button>

                <a class="secondary-link" href="/login">Already have an account?</a>
            </form>
        </section>
    </main>
</body>
</html>
