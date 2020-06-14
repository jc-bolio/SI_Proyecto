<?php
/**
 * Intenta eliminar la publicación especificada
 * Elimina primero los comentarios de la publicación
 * y luego la publicación
 * @param PDO $pdo
 * @param $postId
 * @return bool
 * @throws Exception
 */
function deletePost(PDO $pdo, $postId) {
    $sqls = array("DELETE FROM comentario WHERE post_id = :id",
            "DELETE FROM post WHERE id = :id",);

    foreach ($sqls as $sql) {
        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Hubo un problema al preparar esta consulta');
        }

        $result = $stmt->execute(array('id' => $postId, ));

        // Rompe el foreach si algo sale mal
        if ($result === false) {
            break;
        }
    }

    return $result !== false;
}