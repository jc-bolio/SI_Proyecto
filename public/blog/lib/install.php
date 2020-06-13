<?php
/**
 * Función para instalar el blod
 * @return array
 */
function installBlog(){
    // Obtiene el string del PDO DSN
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
        $pdo = getPDO();
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