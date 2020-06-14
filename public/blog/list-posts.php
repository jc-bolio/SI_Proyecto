<?php
require_once 'lib/common.php';

session_start();

// Se restringe acceso a usuarios no autorizados
if (!isLoggedIn()) {
    redirectExit('index.php');
}
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
        <form method="post">
            <table id="post-list">
                <tbody>
                    <tr>
                        <td>Titulo del primer post</td>
                        <td>
                            dd MM YYYY h:mi
                        </td>
                        <td>
                            <a href="edit-post.php?post_id=1">Editar</a>
                        </td>
                        <td>
                            <input
                                type="submit"
                                name="post[1]"
                                value="Eliminar"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td>Titulo del segundo post</td>
                        <td>
                            dd MM YYYY h:mi
                        </td>
                        <td>
                            <a href="edit-post.php?post_id=2">Editar</a>
                        </td>
                        <td>
                            <input
                                type="submit"
                                name="post[2]"
                                value="Eliminar"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td>Titulo del cuarto post</td>
                        <td>
                            dd MM YYYY h:mi
                        </td>
                        <td>
                            <a href="edit-post.php?post_id=3">Editar</a>
                        </td>
                        <td>
                            <input
                                type="submit"
                                name="post[3]"
                                value="Eliminar"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>
