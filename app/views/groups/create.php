<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Group</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h2>Create Group</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color:green;">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            <?php if (!empty($createdGroupCode)): ?>
                Your group code is
                <strong><?= htmlspecialchars($createdGroupCode, ENT_QUOTES, 'UTF-8') ?></strong>.
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/groups/create">
        <div>
            <label for="group_name">Group Name</label>
            <input
                id="group_name"
                type="text"
                name="group_name"
                value="<?= htmlspecialchars($formData['group_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div>
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                required
            ><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <button type="submit">Create Group</button>
    </form>

    <p><a href="/groups">Back to Groups</a></p>
</body>
</html>
