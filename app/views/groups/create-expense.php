<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/create-expense.css">
</head>
<body class="create-expense-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="create-expense-main">
        <section class="create-expense-card">
            <h1 class="create-expense-title">Add Expense</h1>

            <?php if (!empty($error)): ?>
                <p class="create-expense-message">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form class="create-expense-form" method="POST" action="/groups/expenses/create">
                <input type="hidden" name="group_id" value="<?= htmlspecialchars((string) $group['group_id'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="create-expense-field">
                    <label class="create-expense-label" for="amount">Select amount:</label>
                    <input
                        class="create-expense-input"
                        id="amount"
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0.01"
                        value="<?= htmlspecialchars($formData['amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <div class="create-expense-field">
                    <label class="create-expense-label" for="category">Category</label>
                    <select class="create-expense-select" id="category" name="category" required>
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

                <div class="create-expense-field">
                    <label class="create-expense-label" for="description">Add a description:</label>
                    <textarea
                        class="create-expense-textarea"
                        id="description"
                        name="description"
                        required
                    ><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="create-expense-actions">
                    <button type="submit" class="create-expense-button is-primary">Add</button>
                    <a class="create-expense-button is-secondary" href="/groups/dashboard?id=<?= urlencode((string) $group['group_id']) ?>">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
