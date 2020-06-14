<?php

/**
 * Maneja el formulario para añadir comentarios
 * @param PDO $pdo
 * @param $postId
 * @param array $commentData
 * @return array
 * @throws Exception
 */
function handleAddComment(PDO $pdo, $postId, array $commentData) {
    $errors = addComment(
        $pdo,
        $postId,
        $commentData
    );
    // Si no hay errores, redirije a sí mismo
    if (!$errors) {
        redirectExit('view-post.php?post_id=' . $postId);
    }
    return $errors;
}

/**
 * Maneja el formulario para eliminar comentarios
 * @param PDO $pdo
 * @param $postId
 * @param array $deleteResponse
 * @throws Exception
 */
function handleDeleteComment(PDO $pdo, $postId, array $deleteResponse) {
    if (isLoggedIn()) {
        $keys = array_keys($deleteResponse);
        $deleteCommentId = $keys[0];
        if ($deleteCommentId) {
            deleteComment($pdo, $postId, $deleteCommentId);
        }
        redirectExit('view-post.php?post_id=' . $postId);
    }
}

/**
 * Eliminar el comentario en la publicación especificada
 * @param PDO $pdo
 * @param $postId
 * @param $commentId
 * @return bool
 * @throws Exception
 */
function deleteComment(PDO $pdo, $postId, $commentId) {
    $sql = "DELETE FROM comentario WHERE post_id = :post_id AND id = :comment_id";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Hubo un problema al preparar la consulta');
    }
    $result = $stmt->execute(
        array(
            'post_id' => $postId,
            'comment_id' => $commentId,
        )
    );
    return $result !== false;
}

/**
 * Obtiene un solo post
 * @param PDO $pdo
 * @param $postId
 * @return mixed
 * @throws Exception
 */
function getPostRow(PDO $pdo, $postId){
    $stmt = $pdo->prepare('SELECT titulo, fecha_creacion, cuerpo, 
                        (SELECT COUNT(*) FROM comentario WHERE comentario.post_id = post.id) comment_count
                        FROM post WHERE id = :id');
    if ($stmt === false) throw new Exception('Hubo un problema al preparar este query');

    $result = $stmt->execute(array('id' => $postId, ));

    if ($result === false) {
        throw new Exception('Hubo un problema al ejecutar este query');
    }

// Consulta un registro en la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

/**
 * Escribe un comentario en una publicación
 * @param PDO $pdo
 * @param $postId
 * @param array $commentData
 * @return array
 * @throws Exception
 */
function addComment(PDO $pdo, $postId, array $commentData) {
    $errors = array();
    // Validación
    if (empty($commentData['nombre'])) {
        $errors['nombre'] = 'Se requiere un nombre';
    }
    if (empty($commentData['texto'])) {
        $errors['texto'] = 'Se requiere un comentario';
    }
    // Si no hay errores, trata de escribir el comentario
    if (!$errors) {
        $sql = "INSERT INTO comentario (nombre, website, texto, fecha_creacion, post_id)
            VALUES(:nombre, :website, :texto, :fecha_creacion, :post_id)";
        $stmt = $pdo->prepare($sql);

        if ($stmt === false) {
            throw new Exception('No se puede preparar la declaración para insertar comentario');
        }

        $result = $stmt->execute(array_merge($commentData,
            array('post_id' => $postId, 'fecha_creacion' => getSqlDate(), )));

        if ($result === false) {
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo) {
                $errors[] = $errorInfo[2];
            }
        }
    }
    return $errors;
}