<?php
require_once 'lib/common.php';
require_once 'lib/edit-post.php';
require_once 'lib/view-post.php';

session_start();

// No permitir que usuarios no autorizados vean esta página
if (!isLoggedIn()) {
    redirectExit('index.php');
}

// Valores predeterminados vacíos
$title = $body = '';
// Inicia la base de datos y obtiene el identificador
$pdo = getPDO();

$postId = null;
if (isset($_GET['post_id'])) {
    $post = getPostRow($pdo, $_GET['post_id']);
    if ($post) {
        $postId = $_GET['post_id'];
        $title = $post['titulo'];
        $body = $post['cuerpo'];
    }
}

// Manejo de POST
$errors = array();
if ($_POST) {
    // Validación
    $title = $_POST['post-title'];
    if (!$title) {
        $errors[] = 'El post tiene que tener un titulo';
    }
    $body = $_POST['post-body'];
    if (!$body) {
        $errors[] = 'El post tiene que tener un cuerpo';
    }
    if (!$errors) {
        $pdo = getPDO();
        // Decide si se esta editando o añadiendo un post
        if ($postId) {
            editPost($pdo, $title, $body, $postId);
        }
        else {
            $userId = getAuthUserId($pdo);
            $postId = addPost($pdo, $title, $body, $userId);

            if ($postId === false) {
                $errors[] = 'Publicación fallida';
            }
        }
    }
    if (!$errors) {
        redirectExit('edit-post.php?post_id=' . $postId);
    }

}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog | Nuevo post</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($errors): ?>
            <div class="error box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form method="post" class="post-form user-form">
            <div>
                <label for="post-title">Titulo:</label>
                <input
                    id="post-title"
                    name="post-title"
                    type="text"
                    value="<?php echo htmlSpecial($title) ?>"
                />
            </div>
            <div>
                <label for="post-body">Cuerpo:</label>
                <textarea
                    id="post-body"
                    name="post-body"
                    rows="12"
                    cols="70"
                ><?php echo htmlSpecial($body) ?></textarea>
            </div>
            <div>
                <input
                    type="submit"
                    value="Salvar post"
                />
            </div>
        </form>
    </body>
</html>