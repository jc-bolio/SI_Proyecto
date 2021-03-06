<?php
require_once 'lib/common.php';
require_once 'lib/install.php';

// Se almacenan las cosas en la sesión para sobrevivir el auto redireccionamiento
session_start();
// Solo ejecuta el instalador cuando respondamos al formulario
if ($_POST){
    // Instalación
    $pdo = getPDO();
    list($rowCount, $error) = installBlog($pdo);

    $password = '';
    if (!$error) {
        $username = 'admin';
        list($password, $error) = createUser($pdo, $username);
    }

    $_SESSION['count'] = $rowCount;
    $_SESSION['error'] = $error;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['try-install'] = true;

    // Redirige de POST a GET
    redirectExit('install.php');
}

// Verifica si se realizó la instalación
$attempted = false;
if (isset($_SESSION['try-install'])) {
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    // Quita las variables de sesión, para solo reportar la instalación/falla una vez
    unset($_SESSION['count']);
    unset($_SESSION['error']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['try-install']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Instalador del blog</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php if ($attempted): ?>

            <?php if ($error): ?>
                <div class="error box">
                    <?php echo $error ?>
                </div>
            <?php else: ?>
                <div class="success box">
                    La base de datos y los datos de prueba se crearon correctamente.

                    <?php foreach (array('post', 'comentario') as $tableName): ?>
                        <?php if (isset($count[$tableName])): ?>
                            <?php echo $count[$tableName] ?> nuevos
                            <?php echo $tableName ?> fueron creados.
                        <?php endif ?>
                    <?php endforeach ?>
                    Para el usuario '<?php echo htmlSpecial($username) ?>' la contraseña es
                    <span class="install-password"><?php echo htmlSpecial($password) ?></span>
                    (puede copiarla).
                </div>
                <p>
                    <a href="index.php">Ver el blog</a>,
                    or <a href="install.php">Instalar de nuevo</a>.
                </p>
            <?php endif ?>

        <?php else: ?>
            <p>Presiona el botón instalar para restablecer la base de datos.</p>
            <form method="post">
                <input
                        name="install"
                        type="submit"
                        value="Instalar"
                />
            </form>
        <?php endif ?>
    </body>
</html>