<?php
require_once 'lib/common.php';

// Se prueba versión mínima de PHP
if (version_compare(PHP_VERSION, '5.3.7') < 0) {
    throw new Exception('Este sistema requiere PHP 5.3.7 o posterior');
}

session_start();

// Regresa al index si ya se inició sesión
if (isLoggedIn()) {
    redirectExit('index.php');
}

// Manejo del formulario
$username = '';
if ($_POST) {
    // Inicia la base de datos.
    $pdo = getPDO();
    // Redirige si la contraseña es correcta
    $username = $_POST['username'];
    $correct = tryLogin($pdo, $username, $_POST['password']);
    if ($correct) {
        login($username);
        redirectExit('index.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Blog | Login
        </title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($username): ?>
            <div class="error box">
                El usuario o la contraseña son incorrectos, intenta de nuevo
            </div>
        <?php endif ?>

        <p>Inicia sesión aquí:</p>
        <form method="post" class="user-form">
            <div>
                <label for="username">
                Usuario:
                </label>
                <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo htmlSpecial($username) ?>"
                />
            </div>
            <div>
                <label for="password">
                Contraseña:
                </label>
                <input
                        type="password"
                        id="password"
                        name="password"
                />
            </div>
            <input type="submit" name="submit" value="Login" />
        </form>
    </body>
</html>
