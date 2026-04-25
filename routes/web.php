<?php

$router->get('/', ['AuthController', 'showHome']);

$router->get('/groups', [GroupController::class, 'showGroups']);
$router->get('/groups/dashboard', [GroupController::class, 'showGroupDashboard']);
$router->get('/groups/split', [GroupController::class, 'showSplitCalculation']);
$router->get('/groups/expenses', [GroupController::class, 'showUserExpenses']);
$router->get('/groups/expenses/create', [GroupController::class, 'showCreateExpenseForm']);
$router->post('/groups/expenses/create', [GroupController::class, 'createExpense']);
$router->get('/groups/join', [GroupController::class, 'handleJoinGroup']);
$router->post('/groups/join', [GroupController::class, 'joinGroup']);
$router->get('/groups/create', [GroupController::class, 'showCreateForm']);
$router->post('/groups/create', [GroupController::class, 'createGroup']);
$router->get('/groups/edit', [GroupController::class, 'showEditForm']);
$router->post('/groups/update', [GroupController::class, 'updateGroup']);
$router->post('/groups/members/remove', [GroupController::class, 'removeMember']);
$router->post('/groups/leave', [GroupController::class, 'leaveGroup']);
$router->post('/groups/delete', [GroupController::class, 'deleteGroup']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [AuthController::class, 'showDashboard']);
