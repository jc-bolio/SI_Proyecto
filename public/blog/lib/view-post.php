<?php
/**
 * Obtiene un solo post
 * @param PDO $pdo
 * @param integer $postId
 * @return mixed
 * @throws Exception
 */
function getPostRow(PDO $pdo, $postId){
    $stmt = $pdo->prepare('SELECT titulo, fecha_creacion, cuerpo FROM post WHERE id = :id');
    if ($stmt === false) throw new Exception('Hubo un problema al ejecutar este query');

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
 * @param integer $postId
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