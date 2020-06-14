<?php
/**
 * Intenta eliminar la publicación especificada
 * @param PDO $pdo
 * @param $postId
 * @return bool
 * @throws Exception
 */
function deletePost(PDO $pdo, $postId) {
    $sql = "DELETE FROM post WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Hubo un problema al preparar esta consulta');
    }

    $result = $stmt->execute(array('id' => $postId, ));
    return $result !== false;
}