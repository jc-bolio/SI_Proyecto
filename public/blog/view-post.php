<?php
require_once 'lib/common.php';
require_once 'lib/view-post.php';

// Obtiene el id de la publicacion
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
} else {
    $postId = 0;
}

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = getPDO();
$row = getPostRow($pdo, $postId);

// Si la publicación no existe se maneja el error aquí.
if (!$row) {
    redirectExit('index.php?not-found=1');
}

//Cambia saltos de linea por saltos de párrafo
$bodyText = htmlSpecial($row['cuerpo']);
$paragraphText = str_replace("\n", "</p><p>", $bodyText);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Blog |
            <?php echo htmlSpecial($row['titulo']) ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <h2>
            <?php echo htmlSpecial($row['titulo']) ?>
        </h2>
        <div>
            <?php echo convertSqlDate($row['fecha_creacion']) ?>
        </div>
        <p>
            <?php echo $paragraphText ?>
        </p>

        <h3><?php echo countComments($postId) ?> comments</h3>
        <?php foreach (getComments($postId) as $comment): ?>
            <hr />
            <div class="comment">
                <div class="comment-meta">
                    Comment from
                    <?php echo htmlSpecial($comment['nombre']) ?>
                    on
                    <?php echo convertSqlDate($comment['fecha_creacion']) ?>
                </div>
                <div class="comment-body">
                    <?php echo htmlSpecial($comment['texto']) ?>
                </div>
            </div>
        <?php endforeach ?>
    </body>
</html>
