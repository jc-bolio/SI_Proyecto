<?php
/**
 * Función para instalar el blog
 * @param PDO $pdo
 * @return array
 */
function installBlog(PDO $pdo){
    // Obtiene rutas útiles al proyecto
    $root = getRootPath();
    $database = getDatabasePath();

    $error = '';

    // Medida de seguridad para evitar que alguien restablesca la base de datos si ya existe
    if (is_readable($database) && filesize($database) > 0) {
        $error = 'Elimine la base de datos existente manualmente antes de instalarla nuevamente.';
    }

    // Crea un archivo vacío para la base de datos.
    if (!$error) {
        $creadasBien = @touch($database);
        if (!$creadasBien) {
            $error = sprintf('No se pudo crear la base de datos, 
            permita que el servidor cree nuevos archivos en \'%s\'', dirname($database));
        }
    }

    // Toma el SQL que queremos ejecutar en la base de datos
    if (!$error) {
        $sql = file_get_contents($root . '/data/init.sql');
        if ($sql === false) {
            $error = 'No se pudo encontrar archivo SQL';
        }
    }

    // Se conecta a la nueva base de datos e intenta ejecutar el SQL
    if (!$error) {
        $result = $pdo->exec($sql);
        if ($result === false) {
            $error = 'No se pudo ejecutar SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }

    // Revisa cuántas filas se crearon
    $count = array();
    foreach (array('post', 'comentario') as $tableName) {
        if (!$error){
            $sql = "SELECT COUNT(*) AS c FROM " . $tableName;
            $stmt = $pdo->query($sql);
            if ($stmt){
                $count[$tableName] = $stmt->fetchColumn();
            }
        }
    }

    return array($count, $error);
}

/**
 * Crea un nuevo usuario en la base de datos
 * @param PDO $pdo
 * @param $username
 * @param int $length
 * @return string[]
 */
function createUser(PDO $pdo, $username, $length = 10){
    // Crea un password random
    $alphabet = range(ord('A'), ord('z'));
    $alphabetLength = count($alphabet);
    $password = '';
    for($i = 0; $i < $length; $i++) {
        $letter = $alphabet[rand(0, $alphabetLength - 1)];
        $password .= chr($letter);
    }

    $error = '';
    // Inserta las credenciales en la base de datos
    $sql = "INSERT INTO usuario (username, password, fecha_creacion)
            VALUES (:username, :password, :fecha_creacion)";

    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        $error = 'No se pudo preparar la creación del usuario.';
    }

    if (!$error) {
        //Crea un hash del password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false){
            $error = 'Fallo el hashing del password';
        }
    }

    // Inserta el usuario y el hash
    if (!$error) {
        $result = $stmt->execute(
            array(
                'username' => $username,
                'password' => $hash,
                'fecha_creacion' => getSqlDate(),
            )
        );
        if ($result === false) {
            $error = 'No se pudo crear el usuario.';
        }
    }

    if ($error) {
        $password = '';
    }

    return array($password, $error);
}