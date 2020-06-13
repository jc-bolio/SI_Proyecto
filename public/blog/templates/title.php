<div style="float: right;">
    <?php if (isLoggedIn()): ?>
        Hola <?php echo htmlSpecial(getAuthUser()) ?>.
        <a href="logout.php">Cerrar sesión</a>
    <?php else: ?>
        <a href="login.php">Iniciar sesión</a>
    <?php endif ?>
</div>

<a href="index.php">
    <h1>Título del blog</h1>
</a>
<p>Resumen del contenido del que trata este blog.</p>
