<?php
require_once 'lib/common.php';

// Se prueba versión mínima de PHP
if (version_compare(PHP_VERSION, '5.3.7') < 0) {
    throw new Exception('Este sistema requiere PHP 5.3.7 o posterior');
}

session_start();

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
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($username): ?>
            <div style="border: 1px solid #ff6666; padding: 6px;">
                El usuario o la contraseña son incorrectos, intenta de nuevo
            </div>
        <?php endif ?>

        <p>Inicia sesión aquí:</p>
        <form method="post">
            <p>
                Usuario:
                <input
                        type="text"
                        name="username"
                        value="<?php echo htmlSpecial($username) ?>"
                />
            </p>
            <p>
                Contraseña:
                <input type="password" name="password" />
            </p>
            <input type="submit" name="submit" value="Login" />
        </form>
    </body>
</html>
