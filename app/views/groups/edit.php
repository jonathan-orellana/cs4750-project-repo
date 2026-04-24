<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Group</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1>Edit Group</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($statusMessage)): ?>
        <p style="color:green;">
            <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <strong>Group Code:</strong>
        <?= htmlspecialchars($group['group_code'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <?php if ($canManageGroup): ?>
        <form method="POST" action="/groups/update">
            <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">

            <div>
                <label for="group_name">Group Name</label>
                <input
                    id="group_name"
                    type="text"
                    name="group_name"
                    value="<?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>"
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
                ><?= htmlspecialchars($group['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit">Save Changes</button>
        </form>
    <?php else: ?>
        <p>You can view this group, but only the owner can edit or delete it.</p>
        <p>
            <strong>Group Name:</strong>
            <?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>
        </p>
        <p>
            <strong>Description:</strong>
            <?= htmlspecialchars($group['description'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <h2>Abandon Group</h2>

        <form method="POST" action="/groups/leave" onsubmit="return confirm('This action cannot be undone. Are you sure you want to leave this group?');">
            <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Abandon Group</button>
        </form>
    <?php endif; ?>

    <h2>Members</h2>

    <?php if (empty($members)): ?>
        <p>This group has no members.</p>
    <?php else: ?>
        <?php foreach ($members as $member): ?>
            <section style="margin-bottom: 16px;">
                <p>
                    <strong>Name:</strong>
                    <?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>
                    <strong>Email:</strong>
                    <?= htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>
                    <strong>Role:</strong>
                    <?= htmlspecialchars($member['role'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <?php if ($canManageGroup && (int) $member['user_id'] !== (int) $group['created_by_user_id']): ?>
                    <form method="POST" action="/groups/members/remove">
                        <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="member_user_id" value="<?= htmlspecialchars((string) $member['user_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit">Remove Member</button>
                    </form>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($canManageGroup): ?>
        <h2>Delete Group</h2>

        <form method="POST" action="/groups/delete" onsubmit="return confirm('This action cannot be undone. Are you sure you want to delete this group?');">
            <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Delete Group</button>
        </form>
    <?php endif; ?>

    <p><a href="/groups">Back to Groups</a></p>
</body>
</html>
