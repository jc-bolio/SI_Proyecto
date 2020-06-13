<!DOCTYPE html>
<html>
    <head>
        <title>
            Blog | Login
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    </head>
    <body>
        <?php require 'templates/title.php' ?>
        <p>Inicia sesión aquí:</p>
        <form method="post">
            <p>
                Usuario:
                <input type="text" name="username" />
            </p>
            <p>
                Contraseña:
                <input type="password" name="password" />
            </p>
            <input type="submit" name="submit" value="Login" />
        </form>
    </body>
</html>
