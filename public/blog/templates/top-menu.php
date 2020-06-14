<div class="top-menu">
    <div class="menu-options">
        <?php if (isLoggedIn()): ?>
            <a href="edit-post.php">Nuevo post</a>
            |
            Hola <?php echo htmlSpecial(getAuthUser()) ?>.
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Inicia sesión</a>
        <?php endif ?>
    </div>
</div>