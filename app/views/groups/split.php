<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Split Calculation</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/split.css">
</head>
<body class="split-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="split-main">
        <header class="split-header">
            <div>
                <h1 class="split-title"><?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>'s Split</h1>
            </div>
            <a class="split-button" href="/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>">Back to Dashboard</a>
        </header>

        <section class="split-card">
            <h2>Payments</h2>

            <?php if (empty($payments)): ?>
                <p class="split-empty">The group is already even. No payments are needed.</p>
            <?php else: ?>
                <div class="split-payments">
                    <?php foreach ($payments as $payment): ?>
                        <article class="split-payment">
                            <span class="split-payment__text">
                                <span class="split-payment__name"><?= htmlspecialchars($payment['from'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="split-payment__verb">pays</span>
                                <span class="split-payment__name"><?= htmlspecialchars($payment['to'], ENT_QUOTES, 'UTF-8') ?></span>
                            </span>
                            <span class="split-payment__text split-payment__amount">
                                $<?= htmlspecialchars(number_format((float) $payment['amount'], 2), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
