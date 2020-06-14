<?php
require_once 'lib/common.php';
require_once 'lib/list-posts.php';

session_start();

// Se restringe acceso a usuarios no autorizados
if (!isLoggedIn()) {
    redirectExit('index.php');
}

if ($_POST) {
    $deleteResponse = $_POST['delete-post'];
    if ($deleteResponse) {
        $keys = array_keys($deleteResponse);
        $deletePostId = $keys[0];
        if ($deletePostId) {
            deletePost(getPDO(), $deletePostId);
            redirectExit('list-posts.php');
        }
    }
}

// Se conecta a la base de datos para ejecutar la consulta
$pdo = getPDO();
$posts = getAllPosts($pdo);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog | Posts</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/top-menu.php' ?>
        <h1>Lista de publicaciones</h1>
        <p>Has hecho <?php echo count($posts) ?> publicaciones.

        <form method="post">
            <table id="post-list">
                <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Fecha de creación</th>
                    <th>Comentarios</th>
                    <th />
                    <th />
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <a href="view-post.php?post_id=<?php echo $post['id']?>"
                                ><?php echo htmlSpecial($post['titulo']) ?></a>
                            </td>
                            <td>
                                <?php echo convertSqlDate($post['fecha_creacion']) ?>
                            </td>
                            <td>
                                <?php echo $post['comment_count'] ?>
                            </td>
                            <td>
                                <a href="edit-post.php?post_id=<?php echo $post['id']?>">Editar</a>
                            </td>
                            <td>
                                <input
                                        type="submit"
                                        name="delete-post[<?php echo $post['id']?>]"
                                        value="Eliminar"
                                />
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </form>
    </body>
</html>
