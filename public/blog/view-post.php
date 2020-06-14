<?php
require_once 'lib/common.php';
require_once 'lib/view-post.php';

session_start();

// Obtiene el id de la publicación
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
} else {
    $postId = 0;
}

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = getPDO();
$row = getPostRow($pdo, $postId);
$commentCount = $row['comment_count'];

// Si la publicación no existe se maneja el error aquí.
if (!$row) {
    redirectExit('index.php?not-found=1');
}

$errors = null; // Restablece errores
if ($_POST) { //Detecta si se hace una operación POST
    switch ($_GET['action']) {
        case 'add-comment':
            $commentData = array(
                'nombre' => $_POST['comment-name'],
                'website' => $_POST['comment-website'],
                'texto' => $_POST['comment-text'],
            );
            $errors = handleAddComment($pdo, $postId, $commentData);
            break;
        case 'delete-comment':
            $deleteResponse = $_POST['delete-comment'];
            handleDeleteComment($pdo, $postId, $deleteResponse);
            break;
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
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <div class="post">
            <h2>
                <?php echo htmlSpecial($row['titulo']) ?>
            </h2>
            <div class="date">
                <?php echo convertSqlDate($row['fecha_creacion']) ?>
            </div>

            <?php echo convertToParagraphs($row['cuerpo']) ?>
        </div>

        <?php require 'templates/list-comments.php' ?>

        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
