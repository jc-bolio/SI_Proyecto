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
        datetime('now', '-2 months', '-45 minutes', '+10 seconds')
    );

INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
    VALUES(
        "Segundo post",
        "Cuerpo del segundo post.
Segundo párrafo del post.",
        1,
        datetime('now', '-40 days', '+815 minutes', '+37 seconds')
    );

INSERT INTO post (titulo, cuerpo, user_id, fecha_creacion)
    VALUES(
        "Tercer post",
        "Cuerpo del tercer post.
Segundo párrafo del post.",
        1,
        datetime('now', '-13 days', '+198 minutes', '+51 seconds')
    );

DROP TABLE IF EXISTS comentario;
CREATE TABLE comentario (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    post_id INTEGER NOT NULL,
    fecha_creacion VARCHAR NOT NULL,
    nombre VARCHAR NOT NULL,
    website VARCHAR,
    texto VARCHAR NOT NULL
);
INSERT INTO comentario (post_id, fecha_creacion, nombre, website, texto)
    VALUES(
        1,
        datetime('now', '-10 days','+231 minutes', '+7 seconds'),
        'Julio',
        'http://ejemplo.com/',
        "Este es un comentario de Julio"
    );
INSERT INTO comentario (post_id, fecha_creacion, nombre, website, texto)
    VALUES(
        1,
        datetime('now', '-8 days', '+549 minutes', '+32 seconds'),
        'Victor',
        'http://otroejemplo.com/',
        "Victor estuvo aquí"
    );

CREATE TABLE usuario (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    username VARCHAR NOT NULL,
    password VARCHAR NOT NULL,
    fecha_creacion VARCHAR NOT NULL,
    habilitado BOOLEAN NOT NULL DEFAULT true
);