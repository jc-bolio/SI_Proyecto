<?php
require_once 'lib/common.php';

session_start();

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = getPDO();
$stmt = $pdo->query('SELECT id, titulo, fecha_creacion, cuerpo FROM post ORDER BY fecha_creacion DESC');
if ($stmt === false) throw new Exception('Hubo un problema al ejecutar este query');

$notFound = isset($_GET['not-found'])

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($notFound): ?>
            <div style="border: 1px solid #ff6666; padding: 6px;">
                Error: No se pudo encontrar el post solicitado.
            </div>
        <?php endif ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <h2>
                <?php echo htmlSpecial($row['titulo']) ?>
            </h2>
            <div>
                <?php echo convertSqlDate($row['fecha_creacion']) ?>
                (<?php echo countComments($row['id']) ?> comentarios)
            </div>
            <p>
                <?php echo htmlSpecial($row['cuerpo']) ?>
            </p>
            <p>
                <a
                    href="view-post.php?post_id= <?php echo $row['id'] ?>"
                >Leer más...</a>
            </p>
        <?php endwhile; ?>
    </body>
</html>