<?php
$groupId = (string) $group['group_id'];
$currentUserId = (int) Session::get('user_id', 0);
$currentMember = null;

foreach ($splitSummary['members'] as $member) {
    if ((int) $member['user_id'] === $currentUserId) {
        $currentMember = $member;
        break;
    }
}

$yourExpenses = (float) ($currentMember['amount_spent'] ?? 0);
$amountOwed = (float) ($currentMember['amount_owed'] ?? 0);
$expectedReturn = (float) ($currentMember['expected_return'] ?? 0);
$visibleSplitMembers = array_slice($splitSummary['members'], 0, 3);
$hasActiveFilters = !empty($filters['category']) || !empty($filters['payer']) || !empty($filters['date']) || !empty($filters['sort']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Dashboard</title>
    <?php require __DIR__ . '/../partials/fonts.php'; ?>
    <link rel="stylesheet" href="/styles/dashboard.css">
</head>
<body class="group-dashboard-page">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="dashboard-wrap">
        <section class="dashboard-section overview-section" aria-labelledby="dashboard-title">
            <div class="section-inner">
                <header class="page-title">
                    <h1 id="dashboard-title">Group Expenses</h1>
                    <p class="group-meta">
                        <strong>Group ID:</strong>
                        <?= htmlspecialchars($group['group_code'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </header>

                <?php if (!empty($statusMessage)): ?>
                    <p class="status-message">
                        <?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <div class="panel hero-panel" aria-labelledby="total-spent-heading">
                    <div class="hero-total">
                        <span class="amount-xl">$<?= htmlspecialchars(number_format((float) $totalExpenseAmount, 2), ENT_QUOTES, 'UTF-8') ?></span>
                        <span id="total-spent-heading" class="metric-label">Total Amount Spent</span>
                    </div>

                    <div class="hero-actions">
                        <a class="button" href="/groups/split?id=<?= urlencode($groupId) ?>">Calculate Split</a>
                        <a class="button button-primary" href="/groups/expenses/create?group_id=<?= urlencode($groupId) ?>">Add expense</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section split-section" aria-label="Group expense summary">
            <div class="section-inner">
                <div class="summary-strip">
                    <article class="panel metric-card">
                        <h2>Your expenses</h2>
                        <span class="metric-value">$<?= htmlspecialchars(number_format($yourExpenses, 2), ENT_QUOTES, 'UTF-8') ?></span>
                        <a class="button button-primary" href="/groups/expenses?group_id=<?= urlencode($groupId) ?>">View expenses</a>
                    </article>

                    <article class="panel metric-card">
                        <h2>Amount you owe</h2>
                        <span class="metric-value">$<?= htmlspecialchars(number_format($amountOwed, 2), ENT_QUOTES, 'UTF-8') ?></span>
                    </article>

                    <article class="panel metric-card">
                        <h2>Expected return</h2>
                        <span class="metric-value">$<?= htmlspecialchars(number_format($expectedReturn, 2), ENT_QUOTES, 'UTF-8') ?></span>
                    </article>
                </div>

                <div class="split-content">
                    <article class="panel split-card">
                        <h2>Current Split</h2>

                        <?php if (empty($visibleSplitMembers)): ?>
                            <p class="empty-state">No members found for this group.</p>
                        <?php else: ?>
                            <div class="split-list">
                                <?php foreach ($visibleSplitMembers as $member): ?>
                                    <div class="split-row">
                                        <span class="split-name"><?= htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <span class="split-amount">$<?= htmlspecialchars(number_format((float) $member['amount_owed'], 2), ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a class="view-more" href="/groups/split?id=<?= urlencode($groupId) ?>">View More</a>
                        <?php endif; ?>
                    </article>
                </div>
            </div>
        </section>

        <section class="dashboard-section activity-section" aria-labelledby="activity-heading">
            <div class="section-inner">
                <div class="panel activity-card">
                    <h2 id="activity-heading">Activity</h2>

                    <form class="filters" method="GET" action="/groups/dashboard" data-filter-form>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($groupId, ENT_QUOTES, 'UTF-8') ?>">

                        <label>
                            <span class="filter-label">Category</span>
                            <select name="category" <?= $hasActiveFilters ? '' : 'disabled' ?>>
                                <option value="">Category</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>" <?= ($filters['category'] ?? '') === $c ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>
                            <span class="filter-label">Member</span>
                            <input type="text" name="payer" placeholder="Member" value="<?= htmlspecialchars($filters['payer'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $hasActiveFilters ? '' : 'disabled' ?>>
                        </label>

                        <label>
                            <span class="filter-label">Date</span>
                            <input type="date" name="date" value="<?= htmlspecialchars($filters['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $hasActiveFilters ? '' : 'disabled' ?>>
                        </label>

                        <label>
                            <span class="filter-label">Sort</span>
                            <select name="sort" <?= $hasActiveFilters ? '' : 'disabled' ?>>
                                <option value="date_desc" <?= ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Sort: Newest</option>
                                <option value="date_asc" <?= ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Oldest</option>
                                <option value="amount_desc" <?= ($filters['sort'] ?? '') === 'amount_desc' ? 'selected' : '' ?>>Amount High</option>
                                <option value="amount_asc" <?= ($filters['sort'] ?? '') === 'amount_asc' ? 'selected' : '' ?>>Amount Low</option>
                                <option value="payer_asc" <?= ($filters['sort'] ?? '') === 'payer_asc' ? 'selected' : '' ?>>Payer A-Z</option>
                            </select>
                        </label>

                        <button
                            class="button button-muted filter-toggle"
                            type="button"
                            data-filter-toggle
                            <?= $hasActiveFilters ? 'hidden' : '' ?>
                        >
                            Filter
                        </button>

                        <button class="button button-primary filter-action" type="submit" <?= $hasActiveFilters ? '' : 'hidden' ?>>Apply</button>
                        <a class="clear-link filter-action" href="/groups/dashboard?id=<?= urlencode($groupId) ?>" <?= $hasActiveFilters ? '' : 'hidden' ?>>Clear</a>
                    </form>

                    <?php if (empty($expenses)): ?>
                        <p class="empty-state">No expenses have been added yet.</p>
                    <?php else: ?>
                        <table class="activity-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Member</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expenses as $expense): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(date('m/d/Y', strtotime($expense['expense_date'])), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($expense['paid_by_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($expense['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $expense['amount'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <a class="back-link" href="/groups">Back to Groups</a>
            </div>
        </section>
    </main>
    <script src="/scripts/nav-scroll.js"></script>
    <script src="/scripts/activity-filters.js"></script>
</body>
</html>
