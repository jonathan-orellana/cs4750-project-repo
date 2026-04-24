<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Split Calculation</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1><?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (empty($payments)): ?>
        <p>The group is already even. No payments are needed.</p>
    <?php else: ?>
        <?php foreach ($payments as $payment): ?>
            <p>
                <?= htmlspecialchars($payment['from'], ENT_QUOTES, 'UTF-8') ?>
                pays
                <?= htmlspecialchars($payment['to'], ENT_QUOTES, 'UTF-8') ?>
                $<?= htmlspecialchars(number_format((float) $payment['amount'], 2), ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>">Back to Dashboard</a></p>
</body>
</html>
