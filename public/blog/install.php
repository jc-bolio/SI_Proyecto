<?php
require_once 'lib/common.php';
require_once 'lib/install.php';

// Se almacenan las cosas en la sesión para sobrevivir el auto redireccionamiento
session_start();
// Solo ejecuta el instalador cuando respondamos al formulario
if ($_POST){
    // Instalación
    list($_SESSION['count'], $_SESSION['error']) = installBlog();
    // Redirige de POST a GET
    redirectExit('install.php');
}

// Verifica si se realizó la instalación
$attempted = false;
if ($_SESSION) {
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];
    // Desestablece las variables de sesión, para solo reportar la instalación/falla una vez
    unset($_SESSION['count']);
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Instalador del blog</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <style type="text/css">
            .box {
                border: 1px dotted silver;
                border-radius: 5px;
                padding: 4px;
            }
            .error {
                background-color: #ff6666;
            }
            .success {
                background-color: #88ff88;
            }
        </style>
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
                </div>
                <p>
                    <a href="index.php">Ver el blog</a>,
                    or <a href="install.php">Instalar de nuevo</a>.
                </p>
            <?php endif ?>

        <?php else: ?>
            <p>Presiona el botón instalar para reestablecer la base de datos.</p>
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