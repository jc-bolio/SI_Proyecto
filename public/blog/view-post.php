<?php
// Ruta a la base de datos, para que SQLite / PDO pueda conectarse
$root = __DIR__;
$database = $root . '/data/data.sqlite';
$dsn = 'sqlite:' . $database;

// Obtiene el id de la publicacion
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
} else {
    $postId = 0;
}

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = new PDO($dsn);
$stmt = $pdo->prepare('SELECT titulo, fecha_creacion, cuerpo 
    FROM post WHERE id = :id');
if ($stmt === false) throw new Exception('Hubo un problema al ejecutar este query');

$result = $stmt->execute(array('id' => $postId, ));

if ($result === false) {
    throw new Exception('Hubo un problema al ejecutar este query');
}

// Consulta un registro en la base de datos
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Blog |
            <?php echo htmlspecialchars($row['titulo'], ENT_HTML5, 'UTF-8') ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <h2>
            <?php echo htmlspecialchars($row['titulo'],ENT_HTML5, 'UTF-8')?>
        </h2>
        <div>
            <?php echo $row['fecha_creacion']?>
        </div>
        <p>
            <?php echo htmlspecialchars($row['cuerpo'], ENT_HTML5, 'UTF-8') ?>
        </p>
    </body>
</html>
