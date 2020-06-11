<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    </head>
    <body>
        <h1>Titulo del blog</h1>
        <p>Resumen del contenido del que trata este blog.</p>

        <?php for ($postId = 1; $postId <=3; $postId++): ?>
            <h2>Titulo del articulo <?php echo $postId ?></h2>
            <div>día mes año</div>
            <p>Un párrafo resumiendo el articulo <?php echo $postId ?>.</p>
            <p>
                <a href="#">Leer más...</a>
            </p>
        <?php endfor ?>

    </body>
</html>