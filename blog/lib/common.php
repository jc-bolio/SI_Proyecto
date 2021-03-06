<?php
/**
 * Obtiene la ruta raíz del proyecto
 * @return false|string
 */
function getRootPath() {
    return realpath(__DIR__ . '/..');
}

/**
 * Obtiene la ruta completa para el archivo con la base de datos
 * @return string
 */
function getDatabasePath() {
    return getRootPath() . '/data/data.sqlite';
}

/**
 * Obtiene el Data Source Name (DSN) para la conexión con SQLite
 * @return string
 */
function getDsn() {
    return 'sqlite:' . getDatabasePath();
}

/**
 * Obtiene el objeto PDO para acceder a la base de datos
 * @return PDO
 * @throws Exception
 */
function getPDO() {
    $pdo = new PDO(getDsn());
    $result = $pdo->query('PRAGMA foreign_keys = ON');
    if ($result === false) {
        throw new Exception('No se pudieron activar las restricciones de clave externa');
    }
    return $pdo;
}

/**
 * Convierte caracteres especiales en entidades HTML
 * @param $html
 * @return string
 */
function htmlSpecial($html) {
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

/**
 * Convierte el fomato de la fecha almacenada en la base de datos
 * @param $sqlDate
 * @return string
 */
function convertSqlDate($sqlDate) {
    /* @var $date DateTime */
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);
    $date->setTimezone(new DateTimeZone('America/Mexico_City'));
    return $date->format('d-m-Y, H:i');
}

/**
 * Retorna el tiempo en un formato para la base de datos.
 * @return false|string
 */
function getSqlDate() {
    return date('Y-m-d H:i:s');
}

/**
 * @param PDO $pdo
 * @return array
 * @throws Exception
 */
function getAllPosts(PDO $pdo){
    $stmt = $pdo->query('SELECT id, titulo, fecha_creacion, cuerpo, 
            (SELECT COUNT(*) FROM comentario WHERE comentario.post_id = post.id) comment_count
            FROM post ORDER BY fecha_creacion DESC');
    if ($stmt === false) {
        throw new Exception('Hubo un problema al ejecutar este query');
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Convierte texto inseguro a HTML seguro, con saltos de párrafo
 * @param $text
 * @return string
 */
function convertToParagraphs($text){
    $escaped = htmlSpecial($text);
    return '<p>' . str_replace("\n", "</p><p>", $escaped) . '</p>';
}

/**
 * Maneja solicitudes del blog que no existen
 * @param $script
 */
function redirectExit($script) {
    // Obtiene la URL relativa al dominio
    $relativeURL = $_SERVER['PHP_SELF'];
    $folderURL = substr($relativeURL, 0, strrpos($relativeURL, '/') + 1);

    // Redirecciona a la URL completa
    $host = $_SERVER['HTTP_HOST'];
    $fullURL = 'http://' . $host . $folderURL . $script;
    header('Location: ' . $fullURL);
    exit();
}



/**
 * Devuelve todos los comentarios para el post especificado
 * @param PDO $pdo
 * @param $postId
 * @return array
 */
function getComments(PDO $pdo, $postId) {
    $pdo = getPDO();
    $sql = "SELECT id, nombre, texto, fecha_creacion, website FROM comentario WHERE post_id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('post_id' => $postId, ));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 *
 * @param PDO $pdo
 * @param $username
 * @param $password
 * @return bool
 */
function tryLogin(PDO $pdo, $username, $password){
    $sql = "SELECT password FROM usuario WHERE username = :username AND habilitado = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('username' => $username, ));

    // Obtiene el hash de esta fila y lo verifica
    $hash = $stmt->fetchColumn();
    $success = password_verify($password, $hash);

    return $success;
}

/**
 * Inicia la sesión del usuario
 * Por seguridad se regenera la cookie de la sesión
 * @param $username
 */
function login($username) {
    session_regenerate_id();
    $_SESSION['logged_in_username'] = $username;
}

/**
 * Cierra la sesión del usuario
 *
 */
function logout() {
    unset($_SESSION['logged_in_username']);
}

/**
 *
 * @return mixed|null
 */
function getAuthUser() {
    return isLoggedIn() ? $_SESSION['logged_in_username'] : null;
}

/**
 *
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in_username']);
}

/**
 * Busca user_id para el usuario en la sesión actual
 * @param PDO $pdo
 * @return mixed|null
 */
function getAuthUserId(PDO $pdo) {
    // Regresa nulo si no se ha iniciado sesión
    if (!isLoggedIn()) {
        return null;
    }
    $sql = "SELECT id FROM usuario WHERE username = :username AND habilitado = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('username' => getAuthUser()));

    return $stmt->fetchColumn();
}