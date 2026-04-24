<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Groups</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1>Your Groups</h1>

    <?php if (!empty($statusMessage)): ?>
        <p style="color:green;">
            <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p><a href="/groups/create">Create Another Group</a></p>
    <p><a href="/">Back to Home</a></p>

    <?php if (empty($groups)): ?>
        <p>You are not part of any groups yet.</p>
    <?php else: ?>
        <?php foreach ($groups as $group): ?>
            <section
                style="margin-bottom: 24px; cursor: pointer;"
                onclick="window.location.href='/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>';"
            >
                <h2><?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p>
                    <strong>Your Role:</strong>
                    <?= htmlspecialchars($group['user_role'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>
                    <strong>Members:</strong>
                    <?= htmlspecialchars((string) $group['member_count'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>
                    <a href="/groups/edit?id=<?= urlencode((string) $group['group_id']) ?>">Edit Group</a>
                </p>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
