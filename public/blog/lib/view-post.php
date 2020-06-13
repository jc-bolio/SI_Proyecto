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