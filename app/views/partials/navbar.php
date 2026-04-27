<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isHomeActive = $currentPath === '/';
$isGroupsActive = str_starts_with($currentPath, '/groups');
?>
<nav class="site-nav">
    <div class="site-nav__links">
        <a class="site-nav__link <?= $isHomeActive ? 'is-active' : '' ?>" href="/">Home</a>
        <a class="site-nav__link <?= $isGroupsActive ? 'is-active' : '' ?>" href="/groups">Groups</a>
    </div>
    <div class="site-nav__actions">
        <?php if (Session::has('user_id')): ?>
            <form method="POST" action="/logout" class="site-nav__form">
                <button type="submit" class="site-nav__button">Logout</button>
            </form>
        <?php else: ?>
            <a class="site-nav__button" href="/login">Login</a>
        <?php endif; ?>
    </div>
</nav>
