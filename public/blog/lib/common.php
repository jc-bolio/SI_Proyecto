<?php
/**
 * Obtiene la ruta raíz del proyecto
 *
 * @return string
 */
function getRootPath() {
    return realpath(__DIR__ . '/..');
}
/**
 * Obtiene la ruta completa para el archivo con la base de datos
 *
 * @return string
 */
function getDatabasePath() {
    return getRootPath() . '/data/data.sqlite';
}
/**
 * Obtiene el Data Source Name (DSN) para la conexión con SQLite
 *
 * @return string
 */
function getDsn() {
    return 'sqlite:' . getDatabasePath();
}
/**
 * Obtiene el objeto PDO para acceder a la base de datos
 *
 * @return \PDO
 */
function getPDO() {
    return new PDO(getDsn());
}

/**
 * Convierte caracteres especiales en entidades HTML
 * @param string $html
 * @return string
 */
function htmlSpecial($html) {
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

/**
 * Convierte el fomato de la fecha almacenada en la base de datos
 * @param string $sqlDate
 * @return string
 */
function convertSqlDate($sqlDate) {
    /* @var $date DateTime */
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);
    return $date->format('d-m-Y, H:i');
}

/**
 * Devuelve el número de comentarios para el post especificado
 * @param integer $postId
 * @return integer
 */
function countComments($postId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) c FROM comentario WHERE post_id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('post_id' => $postId, ));
    return (int) $stmt->fetchColumn();
}

/**
 * Devuelve todos los comentarios para el post especificado
 * @param integer $postId
 * @return array
 */
function getComments($postId) {
    $pdo = getPDO();
    $sql = "SELECT id, nombre, texto, fecha_creacion, website FROM comentario WHERE post_id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('post_id' => $postId, ));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}