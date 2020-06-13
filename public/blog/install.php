<?php
require_once 'lib/common.php';

function installBlog(){
    // Obtiene el string del PDO DSN
    $root = getRootPath();
    $database = getDatabasePath();

    $error = '';

    // Medida de seguridad para evitar que alguien restablesca la base de datos si ya existe
    if (is_readable($database) && filesize($database) > 0) {
        $error = 'Elimine la base de datos existente manualmente antes de instalarla nuevamente.';
    }

    // Crea un archivo vacío para la base de datos.
    if (!$error) {
        $creadasBien = @touch($database);
        if (!$creadasBien) {
            $error = sprintf('No se pudo crear la base de datos, 
            permita que el servidor cree nuevos archivos en \'%s\'', dirname($database));
        }
    }

    // Toma los comandos SQL que queremos ejecutar en la base de datos
    if (!$error) {
        $sql = file_get_contents($root . '/data/init.sql');
        if ($sql === false) {
            $error = 'No se pudo encontrar archivo SQL';
        }
    }

    // Conexión a la nueva base de datos e intenta ejecutar los comandos SQL
    if (!$error) {
        $pdo = getPDO();
        $result = $pdo->exec($sql);
        if ($result === false) {
            $error = 'No se pudo ejecutar SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }

    // Revisa cuántas filas se crearon
    $count = array();
    foreach (array('post', 'comentario') as $tableName) {
        if (!$error){
            $sql = "SELECT COUNT(*) AS c FROM " . $tableName;
            $stmt = $pdo->query($sql);
            if ($stmt){
                $count[$tableName] = $stmt->fetchColumn();
            }
        }
    }

    return array($count, $error);
}

// Se almacenan las cosas en la sesión para sobrevivir el auto redireccionamiento
session_start();
// Solo ejecuta el instalador cuando respondamos al formulario
if ($_POST){
    // Instalación
    list($_SESSION['count'], $_SESSION['error']) = installBlog();
    // Redirige de POST a GET
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['REQUEST_URI'];
    header('Location: http://' . $host . $script);
    exit();
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