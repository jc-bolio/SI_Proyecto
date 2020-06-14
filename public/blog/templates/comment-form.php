<?php
/**
 * @var $errors string
 * @var $commentData array
 */
?>

<?php if ($errors): ?> // Reporta los errores en una lista
    <div class="error box comment-margin">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<h3>Agrega tu comentario</h3>
<form method="post" class="comment-form">
    <div>
        <label for="comment-name">
            Nombre:
        </label>
        <input
            type="text"
            id="comment-name"
            name="comment-name"
            value="<?php echo htmlSpecial($commentData['nombre']) ?>"
        />
    </div>
    <div>
        <label for="comment-website">
            Sitio web:
        </label>
        <input
            type="text"
            id="comment-website"
            name="comment-website"
            value="<?php echo htmlSpecial($commentData['website']) ?>"
        />
    </div>
    <div>
        <label for="comment-text">
            Comentario:
        </label>
        <textarea
            id="comment-text"
            name="comment-text"
            rows="8"
            cols="70"
        ><?php echo htmlSpecial($commentData['texto']) ?></textarea>
    </div>

    <div>
        <input type="submit" value="Enviar comentario" />
    </div>
</form>