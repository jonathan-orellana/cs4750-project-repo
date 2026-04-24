<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Group</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1>Join Group</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/groups/join">
        <div>
            <label for="group_code">Group Code</label>
            <input
                id="group_code"
                type="text"
                name="group_code"
                value="<?= htmlspecialchars($formData['group_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <button type="submit">Join Group</button>
    </form>

    <p><a href="/groups">Back to Groups</a></p>
</body>
</html>
