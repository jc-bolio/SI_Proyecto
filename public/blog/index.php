<?php
// Ruta a la base de datos, para que SQLite / PDO pueda conectarse
$root = __DIR__;
$database = $root . '/data/data.sqlite';
$dsn = 'sqlite:' . $database;

// Se conecta a la base de datos, ejecuta una consulta, maneja errores
$pdo = new PDO($dsn);
$stmt = $pdo->query('SELECT titulo, fecha_creacion, cuerpo
        FROM post ORDER BY fecha_creacion DESC');
if ($stmt === false){
    throw new Exception('Hubo un problema al ejecutar este query');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
    <h1>Titulo del blog</h1>
    <p>Resumen del contenido del que trata este blog.</p>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <h2>
            <?php echo htmlspecialchars($row['titulo'], ENT_HTML5,'UTF-8')?>
        </h2>
        <div>
            <?php echo $row['fecha_creacion'] ?>
        </div>
        <p>
            <?php echo htmlspecialchars($row['cuerpo'], ENT_HTML5, 'UTF-8')?>
        </p>
        <p>
            <a href="#">Leer más...</a>
        </p>
    <?php endwhile; ?>
    </body>
</html>