<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Groups</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/groups-index.css">
</head>
<body class="groups-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="groups-main">
        <header class="groups-header">
            <h1 class="groups-title">Groups</h1>
            <a class="groups-create-button" href="/groups/create">Create Group</a>
        </header>

        <?php if (!empty($statusMessage)): ?>
            <p class="groups-status">
                <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <?php if (empty($groups)): ?>
            <p class="groups-empty">You are not part of any groups yet.</p>
        <?php else: ?>
            <div class="groups-list">
                <?php foreach ($groups as $group): ?>
                    <section class="group-card">
                        <div class="group-card__content">
                            <h2 class="group-card__title"><?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="group-card__meta">
                                <?= htmlspecialchars((string) $group['member_count'], ENT_QUOTES, 'UTF-8') ?>
                                <?= ((int) $group['member_count'] === 1) ? 'Member' : 'Members' ?>
                            </p>
                        </div>

                        <div class="group-card__actions">
                            <a
                                class="group-card__dashboard"
                                href="/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>"
                            >
                                Dashboard
                            </a>
                            <a
                                class="group-card__edit"
                                href="/groups/edit?id=<?= urlencode((string) $group['group_id']) ?>"
                                aria-label="Edit group"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" aria-hidden="true">
                                    <path d="M535.6 85.7C513.7 63.8 478.3 63.8 456.4 85.7L432 110.1L529.9 208L554.3 183.6C576.2 161.7 576.2 126.3 554.3 104.4L535.6 85.7zM236.4 305.7C230.3 311.8 225.6 319.3 222.9 327.6L193.3 416.4C190.4 425 192.7 434.5 199.1 441C205.5 447.5 215 449.7 223.7 446.8L312.5 417.2C320.7 414.5 328.2 409.8 334.4 403.7L496 241.9L398.1 144L236.4 305.7zM160 128C107 128 64 171 64 224L64 480C64 533 107 576 160 576L416 576C469 576 512 533 512 480L512 384C512 366.3 497.7 352 480 352C462.3 352 448 366.3 448 384L448 480C448 497.7 433.7 512 416 512L160 512C142.3 512 128 497.7 128 480L128 224C128 206.3 142.3 192 160 192L256 192C273.7 192 288 177.7 288 160C288 142.3 273.7 128 256 128L160 128z"/>
                                </svg>
                            </a>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
