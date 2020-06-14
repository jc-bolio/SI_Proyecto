<?php

/**
 * Añade una publicación
 * @param PDO $pdo
 * @param $title
 * @param $body
 * @param $userId
 * @return string
 * @throws Exception
 */
function addPost(PDO $pdo, $title, $body, $userId) {
    // Prepare la consulta
    $sql = "INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
            VALUES (:titulo, :cuerpo, :user_id, :fecha_creacion)";

    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        throw new Exception('No se pudo preparar el query para insertar el post');
    }
    // Ejecuta el query con los siguientes parámetros
    $result = $stmt->execute(
        array(
            'titulo' => $title,
            'cuerpo' => $body,
            'user_id' => $userId,
            'fecha_creacion' => getSqlDate(),
        )
    );

    if ($result === false) {
        throw new Exception('No se pudo insertar el post');
    }
    return $pdo->lastInsertId();
}

/**
 * Actualiza una publicación
 * @param PDO $pdo
 * @param $title
 * @param $body
 * @param $postId
 * @return bool
 * @throws Exception
 */
function editPost(PDO $pdo, $title, $body, $postId) {
    // Prepare la consulta
    $sql = "UPDATE post SET titulo = :titulo, cuerpo = :cuerpo WHERE id = :post_id";
    $stmt = $pdo->prepare($sql);

    if ($stmt === false) {
        throw new Exception('No se pudo preparar el query para actualizar el post');
    }
    // Ejecuta el query con los siguientes parámetros
    $result = $stmt->execute(
        array(
            'titulo' => $title,
            'cuerpo' => $body,
            'post_id' => $postId,
        )
    );
    if ($result === false) {
        throw new Exception('No se pudo actualizar el post');
    }
    return true;
}