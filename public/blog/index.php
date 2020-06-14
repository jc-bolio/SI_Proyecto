<?php
require_once 'lib/common.php';

session_start();

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = getPDO();
$posts = getAllPosts($pdo);

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
            <?php foreach ($posts as $post): ?>
                <div class="post-synopsis">
                    <h2>
                        <?php echo htmlSpecial($post['titulo']) ?>
                    </h2>
                    <div class="meta">
                        <?php echo convertSqlDate($post['fecha_creacion']) ?>
                        (<?php echo countComments($pdo, $post['id']) ?> comentarios)
                    </div>
                    <p>
                        <?php echo htmlSpecial($post['cuerpo']) ?>
                    </p>
                    <div class="post-controls">
                        <a
                            href="view-post.php?post_id=<?php echo $post['id'] ?>"
                        >Leer más...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a href="edit-post.php?post_id=<?php echo $post['id'] ?>"
                            >Editar</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </body>
</html>