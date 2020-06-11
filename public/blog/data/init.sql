/**
    Script de creación de base de datos
 */

DROP TABLE IF EXISTS post;
CREATE TABLE post (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    titulo VARCHAR NOT NULL,
    cuerpo VARCHAR NOT NULL,
    user_id INTEGER NOT NULL,
    fecha_creacion VARCHAR NOT NULL,
    fecha_actualizacion VARCHAR
);

INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
    VALUES(
        "Primer post",
        "Cuerpo del primer post.
Segundo párrafo del post.",
        1,
        date('now', '-2 months')
    );

INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
    VALUES(
        "Segundo post",
        "Cuerpo del segundo post.
Segundo párrafo del post.",
        1,
        date('now', '-40 days')
    );

INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
    VALUES(
        "Tercer post",
        "Cuerpo del tercer post.
Segundo párrafo del post.",
        1,
        date('now', '-13 days')
    );