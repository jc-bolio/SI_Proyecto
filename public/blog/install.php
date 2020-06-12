<?php
require_once 'lib/common.php';

$error = '';

// Medida de seguridad para evitar que alguien restablesca la base de datos si ya existe
if (is_readable($database) && filesize($database) > 0) {
    $error = 'Elimine la base de datos existente manualmente antes de instalarla nuevamente.';
}

// Crea un archivo vacío para la base de datos.
if (!$error) {
    $creadasBien = @touch($database);
    if (!$creadasBien) {
        $error = sprintf('No se pudo crear la base de datos, permita que el servidor cree nuevos archivos en \'%s\'', dirname($database));
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
$count = null;
if (!$error) {
    $sql = "SELECT COUNT(*) AS c FROM post";
    $stmt = $pdo->query($sql);
    if ($stmt) {
        $count = $stmt->fetchColumn();
    }
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
    <?php if ($error): ?>
        <div class="error box">
            <?php echo $error ?>
        </div>
    <?php else: ?>
        <div class="success box">
            La base de datos y los datos de prueba se crearon correctamente.
            <?php if ($count): ?>
                <?php echo $count ?> nuevas filas se crearon.
            <?php endif ?>
        </div>
    <?php endif ?>
    </body>
</html>