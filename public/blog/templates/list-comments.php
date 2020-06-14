<?php
/**
 * @var $pdo PDO
 * @var $postId integer
 * @var $commentCount integer
 */
?>
<form
    action="view-post.php?action=delete-comment&amp;post_id=<?php echo $postId?>&amp;"
    method="post"
    class="comment-list"
>
    <h3><?php echo $commentCount ?> comentarios</h3>
    <?php foreach (getComments($pdo, $postId) as $comment): ?>
        <div class="comment">
            <div class="comment-meta">
                Comentario de
                <?php echo htmlSpecial($comment['nombre']) ?>
                el
                <?php echo convertSqlDate($comment['fecha_creacion']) ?>
                <?php if (isLoggedIn()): ?>
                    <input
                        type="submit"
                        name="delete-comment[<?php echo $comment['id'] ?>]"
                        value="Eliminar"
                    />
                <?php endif ?>
            </div>
            <div class="comment-body">
                <?php echo convertToParagraphs($comment['texto']) ?>
            </div>
        </div>
    <?php endforeach ?>
</form>