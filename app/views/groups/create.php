<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group - SplitCost Travel</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/home.css">
    <link rel="stylesheet" href="/styles/global.css">
</head>
<body class="home-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="home-hero">
        <div class="hero-content">
            <h1>Create a <span>Group</span></h1>

            <?php if (!empty($error)): ?>
                 <div style="color:red;">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div style="color:green;">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    <?php if (!empty($createdGroupCode)): ?>
                        <br>Invite Code: <strong style="color: Green;"><?= htmlspecialchars($createdGroupCode, ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/groups/create">
                
                <div style="margin-bottom: 20px;">
                    <label for="group_name" style="display: block; margin-bottom: 8px; color: #ccc; font-size: 15px; text-transform: uppercase; letter-spacing: 1px;">Group Name</label>
                    <input
                        id="group_name"
                        type="text"
                        name="group_name"
                        style="width: 100%; padding: 14px; border-radius: 8px; border: 0; background: transparent; color: black; font-size: 20px;"
                        placeholder="e.g. New York 2027"
                        value="<?= htmlspecialchars($formData['group_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <div style="margin-bottom: 30px;">
                    <label for="description" style="display: block; margin-bottom: 8px; color: #ccc; font-size: 15px; text-transform: uppercase; letter-spacing: 1px;">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        style="width: 100%; padding: 14px; border-radius: 8px; border: 0; background: transparent; color: black; font-size: 20px;"
                        placeholder="Trip description"
                        required
                    ><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="hero-actions" style="display: flex; flex-direction: column; gap: 12px;">
                    <button type="submit" class="button button-primary" style="border: none; cursor: pointer; width: 100%; font-size: 20px; padding: 14px;">
                        Create Group
                    </button>
                    
                    <a class="button button-muted" href="/groups" style="text-align: center; width: 100%; font-size: 20px; padding: 14px; text-decoration: none;">
                        Back to Groups
                    </a>
                </div>
            </form>
        </div>

        <svg class="finance-chart" viewBox="0 0 1440 390" preserveAspectRatio="none" aria-hidden="true">
            <polyline class="chart-line chart-line-soft" points="0,150 90,150 140,95 210,95 260,145 325,95 390,95 455,245 520,145 590,0 655,95 730,95 790,95 850,150 910,150 970,95 1030,150 1095,150 1160,150 1225,95 1290,95 1360,95 1440,45"></polyline>
            <polyline class="chart-line chart-line-main" points="0,235 90,235 145,185 260,185 325,185 390,95 455,0 520,95 655,95 730,95 790,235 850,185 910,235 970,235 1030,315 1095,95 1160,235 1225,0 1290,95 1360,95 1440,235"></polyline>
        </svg>
    </main>
</body>
</html>