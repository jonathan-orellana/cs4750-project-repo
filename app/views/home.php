<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SplitCost Travel</title>
    <?php require __DIR__ . '/partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/home.css">
</head>
<body class="home-page">
    <?php require __DIR__ . '/partials/navbar.php'; ?>

    <main class="home-hero">
        <div class="hero-content">
            <h1>Split<span>Cost</span> Travel</h1>

            <div class="hero-actions">
                <a class="button button-primary" href="/groups/create">Create a Group</a>
                <a class="button button-muted" href="/groups/join">Join a Group</a>
            </div>
        </div>

        <svg class="finance-chart" viewBox="0 0 1440 390" preserveAspectRatio="none" aria-hidden="true">
            <polyline class="chart-line chart-line-soft" points="0,150 90,150 140,95 210,95 260,145 325,95 390,95 455,245 520,145 590,0 655,95 730,95 790,95 850,150 910,150 970,95 1030,150 1095,150 1160,150 1225,95 1290,95 1360,95 1440,45"></polyline>
            <polyline class="chart-line chart-line-main" points="0,235 90,235 145,185 260,185 325,185 390,95 455,0 520,95 655,95 730,95 790,235 850,185 910,235 970,235 1030,315 1095,95 1160,235 1225,0 1290,95 1360,95 1440,235"></polyline>
        </svg>
    </main>
</body>
</html>
