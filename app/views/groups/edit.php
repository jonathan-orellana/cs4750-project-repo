<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/edit-group.css">
</head>
<body class="edit-group-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="edit-group-main">
        <header class="edit-group-header">
            <div>
                <p class="edit-group-code">Group Code: <?= htmlspecialchars($group['group_code'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <a class="edit-group-button" href="/groups">Back to Groups</a>
        </header>

        <?php if (!empty($error)): ?>
            <p class="edit-group-message is-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($statusMessage)): ?>
            <p class="edit-group-message is-success">
                <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <div class="edit-group-layout">
            <section class="edit-group-panel">
                <?php if ($canManageGroup): ?>
                    <h2>Group Details</h2>

                    <form id="edit-group-form" class="edit-group-form" method="POST" action="/groups/update">
                        <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">

                        <div class="edit-group-field">
                            <label class="edit-group-label" for="group_name">Group Name</label>
                            <input
                                class="edit-group-input"
                                id="group_name"
                                type="text"
                                name="group_name"
                                value="<?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="edit-group-field">
                            <label class="edit-group-label" for="description">Description</label>
                            <textarea
                                class="edit-group-textarea"
                                id="description"
                                name="description"
                                required
                            ><?= htmlspecialchars($group['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                    </form>
                <?php else: ?>
                    <h2>Group Details</h2>
                    <p class="edit-group-helper">You can view this group, but only the owner can edit or delete it.</p>
                    <div class="edit-group-summary">
                        <div class="edit-group-summary-row">
                            <span class="edit-group-summary-label">Group Name</span>
                            <span class="edit-group-summary-value"><?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="edit-group-summary-row">
                            <span class="edit-group-summary-label">Description</span>
                            <span class="edit-group-summary-value"><?= htmlspecialchars($group['description'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <div class="edit-group-stack">
                <section class="edit-group-panel">
                    <h2>Members</h2>

                    <?php if (empty($members)): ?>
                        <p class="edit-group-empty">This group has no members.</p>
                    <?php else: ?>
                        <div class="edit-group-members">
                            <?php foreach ($members as $member): ?>
                                <article class="edit-group-member">
                                    <div class="edit-group-member-row">
                                        <div class="edit-group-member-cell">
                                            <span class="edit-group-member-label">Name</span>
                                            <h3 class="edit-group-member-name"><?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                        </div>
                                        <div class="edit-group-member-cell">
                                            <span class="edit-group-member-label">Email</span>
                                            <p class="edit-group-member-meta"><?= htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>
                                        <div class="edit-group-member-cell">
                                            <span class="edit-group-member-label">Role</span>
                                            <p class="edit-group-member-meta"><?= htmlspecialchars($member['role'], ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>

                                        <?php if ($canManageGroup && (int) $member['user_id'] !== (int) $group['created_by_user_id']): ?>
                                            <form class="edit-group-member-action" method="POST" action="/groups/members/remove">
                                                <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="member_user_id" value="<?= htmlspecialchars((string) $member['user_id'], ENT_QUOTES, 'UTF-8') ?>">
                                                <button type="submit" class="edit-group-icon-button" aria-label="Remove member">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" aria-hidden="true">
                                                        <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php elseif ($canManageGroup): ?>
                                            <div class="edit-group-member-action edit-group-member-action--placeholder" aria-hidden="true"></div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <?php if ($canManageGroup): ?>
                    <section class="edit-group-panel">
                        <div class="edit-group-danger-actions">
                            <button type="submit" form="edit-group-form" class="edit-group-button">Save Changes</button>
                            <form method="POST" action="/groups/delete" onsubmit="return confirm('This action cannot be undone. Are you sure you want to delete this group?');">
                                <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="edit-group-button is-danger">Delete Group</button>
                            </form>
                        </div>
                    </section>
                <?php else: ?>
                    <section class="edit-group-panel">
                        <div class="edit-group-danger-actions">
                            <form method="POST" action="/groups/leave" onsubmit="return confirm('This action cannot be undone. Are you sure you want to leave this group?');">
                                <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="edit-group-button is-danger">Abandon Group</button>
                            </form>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
