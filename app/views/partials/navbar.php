<nav>
    <a href="/">Home</a>
    <a href="/groups">Groups</a>
    <?php if (Session::has('user_id')): ?>
        <form method="POST" action="/logout" style="display:inline;">
            <button type="submit">Logout</button>
        </form>
    <?php else: ?>
        <a href="/login">Log in</a>
    <?php endif; ?>
</nav>
