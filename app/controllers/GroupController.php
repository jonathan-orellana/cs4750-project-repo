<?php

class GroupController {
    private $userModel;
    private $groupModel;
    private const EXPENSE_CATEGORIES = [
        'Food',
        'Lodging',
        'Transportation',
        'Entertainment',
        'Activities',
        'Shopping',
        'Other'
    ];

    public function __construct() {
        $this->userModel = new User();
        $this->groupModel = new Group();
    }

    public function showGroups() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];

        Response::view(__DIR__ . '/../views/groups/index.php', [
            'groups' => $this->groupModel->getGroupsForUser($userId),
            'statusMessage' => Request::input('message')
        ]);
    }

    public function showGroupDashboard() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $groupId = (int) Request::input('id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, (int) $authenticatedUser['user_id']);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        Response::view(__DIR__ . '/../views/groups/dashboard.php', [
            'group' => $group,
            'totalExpenseAmount' => $this->groupModel->getTotalExpenseAmountForGroup((int) $group['group_id']),
            'expenses' => $this->groupModel->getExpensesForGroup((int) $group['group_id']),
            'splitSummary' => $this->groupModel->getSplitSummaryForGroup((int) $group['group_id']),
            'statusMessage' => Request::input('message')
        ]);
    }

    public function showSplitCalculation() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $groupId = (int) Request::input('id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, (int) $authenticatedUser['user_id']);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        $optimizedSplit = $this->groupModel->getOptimizedSplitForGroup($groupId);

        Response::view(__DIR__ . '/../views/groups/split.php', [
            'group' => $group,
            'splitSummary' => $optimizedSplit['summary'],
            'payments' => $optimizedSplit['payments']
        ]);
    }

    public function handleJoinGroup() {
        Auth::requireUser($this->userModel);

        Response::view(__DIR__ . '/../views/groups/join.php', [
            'formData' => [
                'group_code' => ''
            ],
            'error' => null
        ]);
    }

    public function joinGroup() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $groupCode = strtoupper(trim((string) Request::input('group_code', '')));

        if ($groupCode === '') {
            Response::view(__DIR__ . '/../views/groups/join.php', [
                'formData' => [
                    'group_code' => $groupCode
                ],
                'error' => 'Group code is required.'
            ]);
            return;
        }

        $result = $this->groupModel->joinByCode($groupCode, (int) $authenticatedUser['user_id']);

        if (!$result['success']) {
            Response::view(__DIR__ . '/../views/groups/join.php', [
                'formData' => [
                    'group_code' => $groupCode
                ],
                'error' => $result['message']
            ]);
            return;
        }

        Response::redirect('/groups?message=' . urlencode($result['message']));
    }

    public function showCreateForm() {
        Auth::requireUser($this->userModel);

        Response::view(__DIR__ . '/../views/groups/create.php', [
            'formData' => [
                'group_name' => '',
                'description' => ''
            ],
            'error' => null
        ]);
    }

    public function createGroup() {
        $authenticatedUser = Auth::requireUser($this->userModel);

        $groupName = trim((string) Request::input('group_name', ''));
        $description = trim((string) Request::input('description', ''));
        $formData = [
            'group_name' => $groupName,
            'description' => $description
        ];

        if ($groupName === '' || $description === '') {
            Response::view(__DIR__ . '/../views/groups/create.php', [
                'formData' => $formData,
                'error' => 'Group name and description are required.'
            ]);
            return;
        }

        try {
            $this->groupModel->create(
                $groupName,
                $description,
                (int) $authenticatedUser['user_id']
            );
        } catch (Throwable $exception) {
            Response::view(__DIR__ . '/../views/groups/create.php', [
                'formData' => $formData,
                'error' => 'Unable to create the group right now. Please try again.'
            ]);
            return;
        }

        Response::redirect('/groups?message=' . urlencode('Group created successfully.'));
    }

    public function showCreateExpenseForm() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $groupId = (int) Request::input('group_id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, (int) $authenticatedUser['user_id']);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        Response::view(__DIR__ . '/../views/groups/create-expense.php', [
            'group' => $group,
            'formData' => [
                'amount' => '',
                'description' => '',
                'category' => self::EXPENSE_CATEGORIES[0]
            ],
            'categories' => self::EXPENSE_CATEGORIES,
            'error' => null
        ]);
    }

    public function createExpense() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];
        $groupId = (int) Request::input('group_id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, $userId);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        $amount = trim((string) Request::input('amount', ''));
        $description = trim((string) Request::input('description', ''));
        $category = trim((string) Request::input('category', ''));

        $formData = [
            'amount' => $amount,
            'description' => $description,
            'category' => $category
        ];

        if ($amount === '' || !is_numeric($amount) || (float) $amount <= 0) {
            Response::view(__DIR__ . '/../views/groups/create-expense.php', [
                'group' => $group,
                'formData' => $formData,
                'categories' => self::EXPENSE_CATEGORIES,
                'error' => 'Amount must be a number greater than zero.'
            ]);
            return;
        }

        if ($description === '') {
            Response::view(__DIR__ . '/../views/groups/create-expense.php', [
                'group' => $group,
                'formData' => $formData,
                'categories' => self::EXPENSE_CATEGORIES,
                'error' => 'Description is required.'
            ]);
            return;
        }

        if (!in_array($category, self::EXPENSE_CATEGORIES, true)) {
            Response::view(__DIR__ . '/../views/groups/create-expense.php', [
                'group' => $group,
                'formData' => $formData,
                'categories' => self::EXPENSE_CATEGORIES,
                'error' => 'Please choose a valid category.'
            ]);
            return;
        }

        $this->groupModel->createExpenseForGroup(
            $groupId,
            number_format((float) $amount, 2, '.', ''),
            $description,
            $category,
            $userId
        );

        Response::redirect('/groups/dashboard?id=' . urlencode((string) $groupId) . '&message=' . urlencode('Expense added successfully.'));
    }

    public function showEditForm() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $groupId = (int) Request::input('id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, (int) $authenticatedUser['user_id']);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        Response::view(__DIR__ . '/../views/groups/edit.php', [
            'group' => $group,
            'members' => $this->groupModel->getGroupMembers($groupId),
            'error' => null,
            'statusMessage' => Request::input('message'),
            'canManageGroup' => $this->groupModel->canManageGroup($group, (int) $authenticatedUser['user_id'])
        ]);
    }

    public function updateGroup() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];
        $groupId = (int) Request::input('group_id', 0);
        $groupName = trim((string) Request::input('group_name', ''));
        $description = trim((string) Request::input('description', ''));
        $group = $this->groupModel->findGroupForUser($groupId, $userId);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        if ($groupName === '' || $description === '') {
            Response::view(__DIR__ . '/../views/groups/edit.php', [
                'group' => [
                    'group_id' => $group['group_id'],
                    'group_name' => $groupName,
                    'description' => $description,
                    'group_code' => $group['group_code'],
                    'created_by_user_id' => $group['created_by_user_id'],
                    'user_role' => $group['user_role']
                ],
                'members' => $this->groupModel->getGroupMembers($groupId),
                'error' => 'Group name and description are required.',
                'statusMessage' => null,
                'canManageGroup' => $this->groupModel->canManageGroup($group, $userId)
            ]);
            return;
        }

        if (!$this->groupModel->canManageGroup($group, $userId)) {
            Response::redirect('/groups?message=' . urlencode('You are not allowed to edit that group.'));
        }

        $this->groupModel->updateGroup($groupId, $groupName, $description);

        Response::redirect('/groups?message=' . urlencode('Group updated successfully.'));
    }

    public function removeMember() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];
        $groupId = (int) Request::input('group_id', 0);
        $memberUserId = (int) Request::input('member_user_id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, $userId);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        if (!$this->groupModel->canManageGroup($group, $userId)) {
            Response::redirect('/groups?message=' . urlencode('You are not allowed to remove members from that group.'));
        }

        $removed = $this->groupModel->removeMember($groupId, $memberUserId, $userId);

        $message = $removed
            ? 'Member removed successfully.'
            : 'That member cannot be removed.';

        Response::redirect('/groups/edit?id=' . urlencode((string) $groupId) . '&message=' . urlencode($message));
    }

    public function deleteGroup() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];
        $groupId = (int) Request::input('group_id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, $userId);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        if (!$this->groupModel->canManageGroup($group, $userId)) {
            Response::redirect('/groups?message=' . urlencode('You are not allowed to delete that group.'));
        }

        $this->groupModel->deleteGroup($groupId);

        Response::redirect('/groups?message=' . urlencode('Group deleted successfully.'));
    }

    public function leaveGroup() {
        $authenticatedUser = Auth::requireUser($this->userModel);
        $userId = (int) $authenticatedUser['user_id'];
        $groupId = (int) Request::input('group_id', 0);
        $group = $this->groupModel->findGroupForUser($groupId, $userId);

        if (!$group) {
            Response::redirect('/groups?message=' . urlencode('Group not found.'));
        }

        if ($this->groupModel->canManageGroup($group, $userId)) {
            Response::redirect('/groups/edit?id=' . urlencode((string) $groupId) . '&message=' . urlencode('Owners cannot abandon the group. Owners can delete it instead.'));
        }

        $leftGroup = $this->groupModel->leaveGroup($groupId, $userId);

        $message = $leftGroup
            ? 'You left the group successfully.'
            : 'Unable to leave that group.';

        Response::redirect('/groups?message=' . urlencode($message));
    }
}
