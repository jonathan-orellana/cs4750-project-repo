<?php
$groupId = (string) $group['group_id'];
$total = array_reduce($expenses, function ($carry, $expense) {
    return $carry + (float) $expense['amount'];
}, 0.0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Expenses</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/user-expenses.css">
</head>
<body class="expenses-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="expenses-wrap">
        <header class="page-header">
            <div>
                <h1>Your Expenses</h1>
                <p class="group-meta">
                    <strong>Group ID:</strong>
                    <?= htmlspecialchars($group['group_code'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>

            <a class="button" href="/groups/expenses/create?group_id=<?= urlencode($groupId) ?>">Add expense</a>
        </header>

        <section class="summary-panel" aria-label="Your total expenses">
            <span class="amount-xl">$<?= htmlspecialchars(number_format($total, 2), ENT_QUOTES, 'UTF-8') ?></span>
            <span class="metric-label">Your Total Expenses</span>
        </section>

        <section class="expenses-panel" aria-labelledby="expenses-heading">
            <h2 id="expenses-heading">Expense History</h2>

            <?php if (empty($expenses)): ?>
                <p class="empty-state">You have not added any expenses for this group yet.</p>
            <?php else: ?>
                <table class="expenses-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('m/d/Y', strtotime($expense['expense_date'])), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($expense['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($expense['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>$<?= htmlspecialchars(number_format((float) $expense['amount'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <div class="page-actions">
            <a class="button button-secondary" href="/groups/dashboard?id=<?= urlencode($groupId) ?>">Back to Dashboard</a>
            <a class="button" href="/groups/expenses/create?group_id=<?= urlencode($groupId) ?>">Add more</a>
        </div>
    </main>
</body>
</html>
