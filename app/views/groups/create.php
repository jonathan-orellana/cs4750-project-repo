<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group - SplitCost Travel</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/create-group.css">
</head>
<body class="create-group-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="create-group-main">
        <section class="create-group-card">
            <h1 class="create-group-title">Create a Group</h1>

            <?php if (!empty($error)): ?>
                 <p class="create-group-message is-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <p class="create-group-message is-success">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    <?php if (!empty($createdGroupCode)): ?>
                        <br>Invite Code: <strong><?= htmlspecialchars($createdGroupCode, ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <form class="create-group-form" method="POST" action="/groups/create">
                <div class="create-group-field">
                    <label class="create-group-label" for="group_name">Group Name:</label>
                    <input
                        class="create-group-input"
                        id="group_name"
                        type="text"
                        name="group_name"
                        value="<?= htmlspecialchars($formData['group_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <div class="create-group-field">
                    <label class="create-group-label" for="description">Description:</label>
                    <textarea
                        class="create-group-textarea"
                        id="description"
                        name="description"
                        required
                    ><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="create-group-actions">
                    <button type="submit" class="create-group-button is-primary">
                        Create Group
                    </button>

                    <a class="create-group-button is-secondary" href="/groups">
                        Discard
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
