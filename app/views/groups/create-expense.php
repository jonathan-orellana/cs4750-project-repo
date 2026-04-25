<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <h1>Add Expense</h1>

    <p>
        <strong>Group:</strong>
        <?= htmlspecialchars($group['group_name'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <?php if (!empty($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/groups/expenses/create">
        <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">

        <div>
            <label for="amount">Amount</label>
            <input
                id="amount"
                type="number"
                name="amount"
                step="0.01"
                min="0.01"
                value="<?= htmlspecialchars($formData['amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div>
            <label for="description">Description</label>
            <input
                id="description"
                type="text"
                name="description"
                value="<?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div>
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $categoryOption): ?>
                    <option
                        value="<?= htmlspecialchars($categoryOption, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ($formData['category'] ?? '') === $categoryOption ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($categoryOption, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Add Expense</button>
        <a href="/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>">Cancel</a>
    </form>
</body>
</html>
