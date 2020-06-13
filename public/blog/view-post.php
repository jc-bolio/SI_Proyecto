<?php
require_once 'lib/common.php';
require_once 'lib/view-post.php';

// Obtiene el id de la publicación
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

$errors = null; // Restablece errores
if ($_POST) { //Detecta si se hace una operación POST
    $commentData = array(
        'nombre' => $_POST['comment-name'],
        'website' => $_POST['comment-website'],
        'texto' => $_POST['comment-text'],
    );
    /* Obtiene el nombre del autor, la URL del sitio web, el comentario,
    y los pasa a la función addComment() para su validación y guardado.*/
    $errors = addComment($pdo, $postId, $commentData);
    // Si no hay errores, redirije a sí mismo y vuelve a mostrar
    if (!$errors) {
        redirectExit('view-post.php?post_id=' . $postId);
    }
} else {
    $commentData = array(
        'nombre' => '',
        'website' => '',
        'texto' => '',
    );
}
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
            <?php echo convertToParagraphs($row['cuerpo']) ?>
        </p>

        <h3><?php echo countComments($postId) ?> comentarios</h3>
        <?php foreach (getComments($postId) as $comment): ?>
            <hr />
            <div class="comment">
                <div class="comment-meta">
                    Comentario de
                    <?php echo htmlSpecial($comment['nombre']) ?>
                    el
                    <?php echo convertSqlDate($comment['fecha_creacion']) ?>
                </div>
                <div class="comment-body">
                    <?php echo convertToParagraphs($comment['texto']) ?>
                </div>
            </div>
        <?php endforeach ?>

        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
