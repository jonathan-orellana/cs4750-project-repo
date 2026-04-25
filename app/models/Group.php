<?php

class Group {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function create($groupName, $description, $createdByUserId) {
        $groupCode = $this->generateUniqueGroupCode();

        $this->pdo->beginTransaction();

        try {
            $groupStatement = $this->pdo->prepare(
                'INSERT INTO travel_group (group_name, description, group_code, created_by_user_id)
                 VALUES (:group_name, :description, :group_code, :created_by_user_id)'
            );

            $groupStatement->execute([
                'group_name' => $groupName,
                'description' => $description,
                'group_code' => $groupCode,
                'created_by_user_id' => $createdByUserId
            ]);

            $groupId = (int) $this->pdo->lastInsertId();

            $memberStatement = $this->pdo->prepare(
                'INSERT INTO group_member (group_id, user_id, role, joined_at)
                 VALUES (:group_id, :user_id, :role, :joined_at)'
            );

            $memberStatement->execute([
                'group_id' => $groupId,
                'user_id' => $createdByUserId,
                'role' => 'owner',
                'joined_at' => date('Y-m-d H:i:s')
            ]);

            $this->pdo->commit();

            return [
                'group_id' => $groupId,
                'group_code' => $groupCode
            ];
        } catch (Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    public function joinByCode($groupCode, $userId) {
        $normalizedGroupCode = strtoupper(trim($groupCode));
        $group = $this->findByCode($normalizedGroupCode);

        if (!$group) {
            return [
                'success' => false,
                'message' => 'No group was found with that code.'
            ];
        }

        if ($this->isUserInGroup((int) $group['group_id'], $userId)) {
            return [
                'success' => false,
                'message' => 'You are already a member of that group.'
            ];
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO group_member (group_id, user_id, role, joined_at)
             VALUES (:group_id, :user_id, :role, :joined_at)'
        );

        $statement->execute([
            'group_id' => (int) $group['group_id'],
            'user_id' => $userId,
            'role' => 'member',
            'joined_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'success' => true,
            'group_id' => (int) $group['group_id'],
            'message' => 'You joined the group successfully.'
        ];
    }

    public function getGroupsForUser($userId) {
        $statement = $this->pdo->prepare(
            'SELECT tg.group_id,
                    tg.group_name,
                    tg.description,
                    tg.group_code,
                    tg.created_by_user_id,
                    gm.role AS user_role,
                    COUNT(gm_all.user_id) AS member_count
             FROM travel_group tg
             INNER JOIN group_member gm
                 ON gm.group_id = tg.group_id
             LEFT JOIN group_member gm_all
                 ON gm_all.group_id = tg.group_id
             WHERE gm.user_id = :user_id
             GROUP BY tg.group_id, tg.group_name, tg.description, tg.group_code, tg.created_by_user_id, gm.role
             ORDER BY tg.group_name ASC'
        );

        $statement->execute([
            'user_id' => $userId
        ]);

        return $statement->fetchAll();
    }

    public function getTotalExpenseAmountForGroup($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT COALESCE(SUM(e.amount), 0) AS total_amount
             FROM trip t
             LEFT JOIN expense e
                 ON e.trip_id = t.trip_id
             WHERE t.group_id = :group_id'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        return (float) $statement->fetchColumn();
    }

public function getExpensesForGroup($groupId, $filters = []) {
    $sql =
        'SELECT u.name AS paid_by_name,
                e.category,
                e.description,
                e.amount,
                e.expense_date
         FROM expense e
         INNER JOIN trip t
             ON t.trip_id = e.trip_id
         INNER JOIN users u
             ON u.user_id = e.paid_by_user_id
         WHERE t.group_id = :group_id';

    $params = [
        'group_id' => $groupId
    ];

    if (!empty($filters['category'])) {
        $sql .= ' AND e.category = :category';
        $params['category'] = $filters['category'];
    }

    if (!empty($filters['payer'])) {
        $sql .= ' AND u.name LIKE :payer';
        $params['payer'] = '%' . $filters['payer'] . '%';
    }

    if (!empty($filters['date'])) {
        $sql .= ' AND e.expense_date = :date';
        $params['date'] = $filters['date'];
    }

    $sort = $filters['sort'] ?? 'date_desc';

    if ($sort === 'amount_asc') {
        $sql .= ' ORDER BY e.amount ASC';
    }
    elseif ($sort === 'amount_desc') {
        $sql .= ' ORDER BY e.amount DESC';
    }
    elseif ($sort === 'payer_asc') {
        $sql .= ' ORDER BY u.name ASC';
    }
    elseif ($sort === 'date_asc') {
        $sql .= ' ORDER BY e.expense_date ASC';
    }
    else {
        $sql .= ' ORDER BY e.expense_date DESC, e.expense_id DESC';
    }

    $statement = $this->pdo->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

    public function getExpensesForGroupPaidByUser($groupId, $userId) {
        $statement = $this->pdo->prepare(
            'SELECT e.category,
                    e.description,
                    e.amount,
                    e.expense_date
             FROM expense e
             INNER JOIN trip t
                 ON t.trip_id = e.trip_id
             WHERE t.group_id = :group_id
               AND e.paid_by_user_id = :user_id
             ORDER BY e.expense_date DESC, e.expense_id DESC'
        );

        $statement->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        return $statement->fetchAll();
    }

    public function getSplitSummaryForGroup($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT u.user_id,
                    u.name,
                    COALESCE(SUM(e.amount), 0) AS amount_spent
             FROM group_member gm
             INNER JOIN users u
                 ON u.user_id = gm.user_id
             LEFT JOIN expense e
                 ON e.paid_by_user_id = u.user_id
                AND e.trip_id IN (
                    SELECT trip_id
                    FROM trip
                    WHERE group_id = :group_id
                )
             WHERE gm.group_id = :group_id
             GROUP BY u.user_id, u.name
             ORDER BY u.name ASC'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        $members = $statement->fetchAll();
        $memberCount = count($members);

        if ($memberCount === 0) {
            return [
                'members' => [],
                'total_amount' => 0.0,
                'even_share' => 0.0
            ];
        }

        $totalAmount = array_reduce($members, function ($carry, $member) {
            return $carry + (float) $member['amount_spent'];
        }, 0.0);

        $evenShare = $totalAmount / $memberCount;

        $summary = array_map(function ($member) use ($evenShare) {
            $amountSpent = (float) $member['amount_spent'];
            $balance = $amountSpent - $evenShare;

            return [
                'user_id' => (int) $member['user_id'],
                'name' => $member['name'],
                'amount_spent' => $amountSpent,
                'amount_owed' => max($evenShare - $amountSpent, 0),
                'expected_return' => max($amountSpent - $evenShare, 0),
                'balance' => $balance
            ];
        }, $members);

        return [
            'members' => $summary,
            'total_amount' => $totalAmount,
            'even_share' => $evenShare
        ];
    }

    public function getOptimizedSplitForGroup($groupId) {
        $splitSummary = $this->getSplitSummaryForGroup($groupId);
        $debtors = [];
        $creditors = [];

        foreach ($splitSummary['members'] as $member) {
            $balanceInCents = (int) round($member['balance'] * 100);

            if ($balanceInCents < 0) {
                $debtors[] = [
                    'name' => $member['name'],
                    'amount' => abs($balanceInCents)
                ];
            } elseif ($balanceInCents > 0) {
                $creditors[] = [
                    'name' => $member['name'],
                    'amount' => $balanceInCents
                ];
            }
        }

        usort($debtors, function ($left, $right) {
            return $right['amount'] <=> $left['amount'];
        });

        usort($creditors, function ($left, $right) {
            return $right['amount'] <=> $left['amount'];
        });

        $payments = [];
        $debtorIndex = 0;
        $creditorIndex = 0;

        while ($debtorIndex < count($debtors) && $creditorIndex < count($creditors)) {
            $paymentAmount = min(
                $debtors[$debtorIndex]['amount'],
                $creditors[$creditorIndex]['amount']
            );

            $payments[] = [
                'from' => $debtors[$debtorIndex]['name'],
                'to' => $creditors[$creditorIndex]['name'],
                'amount' => $paymentAmount / 100
            ];

            $debtors[$debtorIndex]['amount'] -= $paymentAmount;
            $creditors[$creditorIndex]['amount'] -= $paymentAmount;

            if ($debtors[$debtorIndex]['amount'] === 0) {
                $debtorIndex++;
            }

            if ($creditors[$creditorIndex]['amount'] === 0) {
                $creditorIndex++;
            }
        }

        return [
            'summary' => $splitSummary,
            'payments' => $payments
        ];
    }

    public function findGroupForUser($groupId, $userId) {
        $statement = $this->pdo->prepare(
            'SELECT tg.group_id,
                    tg.group_name,
                    tg.description,
                    tg.group_code,
                    tg.created_by_user_id,
                    gm.role AS user_role
             FROM travel_group tg
             INNER JOIN group_member gm
                 ON gm.group_id = tg.group_id
             WHERE tg.group_id = :group_id
               AND gm.user_id = :user_id
             LIMIT 1'
        );

        $statement->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        return $statement->fetch();
    }

    public function getGroupMembers($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT u.user_id,
                    u.name,
                    u.email,
                    gm.role,
                    gm.joined_at
             FROM group_member gm
             INNER JOIN users u
                 ON u.user_id = gm.user_id
             WHERE gm.group_id = :group_id
             ORDER BY gm.role ASC, u.name ASC'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        return $statement->fetchAll();
    }

    public function updateGroup($groupId, $groupName, $description) {
        $statement = $this->pdo->prepare(
            'UPDATE travel_group
             SET group_name = :group_name,
                 description = :description
             WHERE group_id = :group_id'
        );

        $statement->execute([
            'group_id' => $groupId,
            'group_name' => $groupName,
            'description' => $description
        ]);
    }

    public function createExpenseForGroup($groupId, $amount, $description, $category, $paidByUserId) {
        $tripId = $this->getDefaultTripIdForGroup($groupId);

        $statement = $this->pdo->prepare(
            'INSERT INTO expense (amount, expense_date, description, category, trip_id, paid_by_user_id)
             VALUES (:amount, :expense_date, :description, :category, :trip_id, :paid_by_user_id)'
        );

        $statement->execute([
            'amount' => $amount,
            'expense_date' => date('Y-m-d'),
            'description' => $description,
            'category' => $category,
            'trip_id' => $tripId,
            'paid_by_user_id' => $paidByUserId
        ]);
    }

    public function removeMember($groupId, $memberUserId, $actingUserId) {
        if ($memberUserId === $actingUserId) {
            return false;
        }

        $group = $this->findGroupById($groupId);

        if (!$group || (int) $group['created_by_user_id'] === $memberUserId) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'DELETE FROM group_member
             WHERE group_id = :group_id
               AND user_id = :user_id'
        );

        $statement->execute([
            'group_id' => $groupId,
            'user_id' => $memberUserId
        ]);

        return $statement->rowCount() > 0;
    }

    public function leaveGroup($groupId, $userId) {
        $group = $this->findGroupById($groupId);

        if (!$group || (int) $group['created_by_user_id'] === $userId) {
            return false;
        }

        $statement = $this->pdo->prepare(
            'DELETE FROM group_member
             WHERE group_id = :group_id
               AND user_id = :user_id'
        );

        $statement->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        return $statement->rowCount() > 0;
    }

    public function deleteGroup($groupId) {
        $tripIds = $this->getTripIdsForGroup($groupId);

        $this->pdo->beginTransaction();

        try {
            if (!empty($tripIds)) {
                $this->deleteExpenseSharesForTrips($tripIds);
                $this->deleteExpensesForTrips($tripIds);
                $this->deletePaymentsForTrips($tripIds);
                $this->deleteActivitiesForTrips($tripIds);
                $this->deleteTrips($tripIds);
            }

            $inviteStatement = $this->pdo->prepare(
                'DELETE FROM group_invite
                 WHERE group_id = :group_id'
            );
            $inviteStatement->execute([
                'group_id' => $groupId
            ]);

            $memberStatement = $this->pdo->prepare(
                'DELETE FROM group_member
                 WHERE group_id = :group_id'
            );
            $memberStatement->execute([
                'group_id' => $groupId
            ]);

            $groupStatement = $this->pdo->prepare(
                'DELETE FROM travel_group
                 WHERE group_id = :group_id'
            );
            $groupStatement->execute([
                'group_id' => $groupId
            ]);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    public function canManageGroup($group, $userId) {
        return (int) $group['created_by_user_id'] === $userId || $group['user_role'] === 'owner';
    }

    private function generateUniqueGroupCode() {
        do {
            $groupCode = strtoupper(bin2hex(random_bytes(4)));
        } while ($this->findByCode($groupCode));

        return $groupCode;
    }

    private function findByCode($groupCode) {
        $statement = $this->pdo->prepare(
            'SELECT group_id, group_name, group_code, created_by_user_id
             FROM travel_group
             WHERE group_code = :group_code
             LIMIT 1'
        );

        $statement->execute([
            'group_code' => $groupCode
        ]);

        return $statement->fetch();
    }

    private function isUserInGroup($groupId, $userId) {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM group_member
             WHERE group_id = :group_id
               AND user_id = :user_id
             LIMIT 1'
        );

        $statement->execute([
            'group_id' => $groupId,
            'user_id' => $userId
        ]);

        return $statement->fetchColumn() !== false;
    }

    private function findGroupById($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT group_id, created_by_user_id
             FROM travel_group
             WHERE group_id = :group_id
             LIMIT 1'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        return $statement->fetch();
    }

    private function getTripIdsForGroup($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT trip_id
             FROM trip
             WHERE group_id = :group_id'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        return array_map('intval', array_column($statement->fetchAll(), 'trip_id'));
    }

    private function getDefaultTripIdForGroup($groupId) {
        $statement = $this->pdo->prepare(
            'SELECT trip_id
             FROM trip
             WHERE group_id = :group_id
             ORDER BY trip_id ASC
             LIMIT 1'
        );

        $statement->execute([
            'group_id' => $groupId
        ]);

        $tripId = $statement->fetchColumn();

        if ($tripId !== false) {
            return (int) $tripId;
        }

        $group = $this->findGroupById($groupId);

        $createTripStatement = $this->pdo->prepare(
            'INSERT INTO trip (trip_name, start_date, end_date, group_id)
             VALUES (:trip_name, :start_date, :end_date, :group_id)'
        );

        $today = date('Y-m-d');

        $createTripStatement->execute([
            'trip_name' => 'Default Trip for Group ' . (int) $groupId,
            'start_date' => $today,
            'end_date' => $today,
            'group_id' => $groupId
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    private function deleteExpenseSharesForTrips($tripIds) {
        $placeholders = $this->buildPlaceholders($tripIds);
        $statement = $this->pdo->prepare(
            "DELETE FROM expense_share
             WHERE expense_id IN (
                 SELECT expense_id
                 FROM expense
                 WHERE trip_id IN ($placeholders)
             )"
        );
        $statement->execute($tripIds);
    }

    private function deleteExpensesForTrips($tripIds) {
        $placeholders = $this->buildPlaceholders($tripIds);
        $statement = $this->pdo->prepare(
            "DELETE FROM expense
             WHERE trip_id IN ($placeholders)"
        );
        $statement->execute($tripIds);
    }

    private function deletePaymentsForTrips($tripIds) {
        $placeholders = $this->buildPlaceholders($tripIds);
        $statement = $this->pdo->prepare(
            "DELETE FROM payment
             WHERE trip_id IN ($placeholders)"
        );
        $statement->execute($tripIds);
    }

    private function deleteActivitiesForTrips($tripIds) {
        $placeholders = $this->buildPlaceholders($tripIds);
        $statement = $this->pdo->prepare(
            "DELETE FROM activity
             WHERE trip_id IN ($placeholders)"
        );
        $statement->execute($tripIds);
    }

    private function deleteTrips($tripIds) {
        $placeholders = $this->buildPlaceholders($tripIds);
        $statement = $this->pdo->prepare(
            "DELETE FROM trip
             WHERE trip_id IN ($placeholders)"
        );
        $statement->execute($tripIds);
    }

    private function buildPlaceholders($values) {
        return implode(', ', array_fill(0, count($values), '?'));
    }
}
