<div class="top-menu">
    <div class="menu-options">
        <?php if (isLoggedIn()): ?>
            Hello <?php echo htmlSpecial(getAuthUser()) ?>.
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Inicia sesión</a>
        <?php endif ?>
    </div>
</div>

<a href="index.php">
    <h1>Título del blog</h1>
</a>
<p>Resumen del contenido del que trata este blog.</p>
