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
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($notFound): ?>
            <div class="error box">
                Error: No se pudo encontrar el post solicitado.
            </div>
        <?php endif ?>

        <div class="post-list">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="post-synopsis">
                    <h2>
                        <?php echo htmlSpecial($row['titulo']) ?>
                    </h2>
                    <div class="meta">
                        <?php echo convertSqlDate($row['fecha_creacion']) ?>
                        (<?php echo countComments($pdo, $row['id']) ?> comentarios)
                    </div>
                    <p>
                        <?php echo htmlSpecial($row['cuerpo']) ?>
                    </p>
                    <div class="read-more">
                        <a
                            href="view-post.php?post_id=<?php echo $row['id'] ?>"
                        >Leer más...</a>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </body>
</html>