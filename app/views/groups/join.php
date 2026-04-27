<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Group</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/join-group.css">
</head>
<body class="join-group-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="join-group-main">
        <section class="join-group-card">
            <h1 class="join-group-title">Join Group</h1>

            <?php if (!empty($error)): ?>
                <p class="join-group-message">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form class="join-group-form" method="POST" action="/groups/join">
                <label class="join-group-label" for="group_code">Group Code</label>
                <input
                    class="join-group-input"
                    id="group_code"
                    type="text"
                    name="group_code"
                    placeholder="Enter Group ID"
                    value="<?= htmlspecialchars($formData['group_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

                <button type="submit" class="join-group-button">Join</button>
            </form>

            <p class="join-group-footer">
                Don't have a group ID? 
                <a href="/groups/create">Create a group</a>
            </p>
        </section>
    </main>
</body>
</html>
