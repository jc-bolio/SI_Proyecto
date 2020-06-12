<?php
require_once 'lib/common.php';

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = getPDO();
$stmt = $pdo->query('SELECT id, titulo, fecha_creacion, cuerpo
        FROM post ORDER BY fecha_creacion DESC');
if ($stmt === false) throw new Exception('Hubo un problema al ejecutar este query');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <h2>
                <?php echo htmlSpecial($row['titulo']) ?>
            </h2>
            <div>
                <?php echo convertSqlDate($row['fecha_creacion']) ?>
            </div>
            <p>
                <?php echo htmlSpecial($row['cuerpo']) ?>
            </p>
            <p>
                <a
                    href="view-post.php?post_id= <?php echo $row['id'] ?>"
                >Leer más...</a>
            </p>
        <?php endwhile; ?>
    </body>
</html>