<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Dashboard</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1>Group Dashboard</h1>

    <?php if (!empty($statusMessage)): ?>
        <p style="color:green;">
            <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        <strong>Group Name:</strong>
        <?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <strong>Group Code:</strong>
        <?= htmlspecialchars($group['group_code'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <strong>Total Amount Spent:</strong>
        $<?= htmlspecialchars(number_format((float) $totalExpenseAmount, 2), ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <a href="/groups/expenses/create?group_id=<?= urlencode((string) $group['group_id']) ?>">Add Expense</a>
    </p>

    <h2>Current Split</h2>

    <?php if (empty($splitSummary['members'])): ?>
        <p>No members found for this group.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Current Expense</th>
                    <th>Amount Owed</th>
                    <th>Expected Return</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($splitSummary['members'] as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $member['amount_spent'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $member['amount_owed'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $member['expected_return'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="/groups/split?id=<?= urlencode((string) $group['group_id']) ?>">Calculate Split</a>
    </p>

    <p>
        <a href="/groups/edit?id=<?= urlencode((string) $group['group_id']) ?>">Edit Group</a>
    </p>

    <h2>Expenses</h2>

    <?php if (empty($expenses)): ?>
        <p>No expenses have been added yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Paid By</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?= htmlspecialchars($expense['paid_by_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($expense['category'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($expense['description'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $expense['amount'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="/groups">Back to Groups</a></p>
</body>
</html>
