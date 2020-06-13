<?php
/**
 * @var $errors string
 * @var $commentData array
 */
?>
<hr />

<?php if ($errors): ?> // Reporta los errores en una lista
    <div style="border: 1px solid #ff6666; padding: 6px;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<h3>Agrega tu comentario</h3>
<form method="post">
    <p>
        <label for="comment-name">
            Nombre:
        </label>
        <input
            type="text"
            id="comment-name"
            name="comment-name"
            value="<?php echo htmlSpecial($commentData['nombre']) ?>"
        />
    </p>
    <p>
        <label for="comment-website">
            Sitio web:
        </label>
        <input
            type="text"
            id="comment-website"
            name="comment-website"
            value="<?php echo htmlSpecial($commentData['website']) ?>"
        />
    </p>
    <p>
        <label for="comment-text">
            Comentario:
        </label>
        <textarea
            id="comment-text"
            name="comment-text"
            rows="8"
            cols="70"
        ><?php echo htmlSpecial($commentData['texto']) ?></textarea>
    </p>
    <input type="submit" value="Enviar comentario" />
</form>