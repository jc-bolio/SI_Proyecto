# Proyecto Sistemas de Información

### Equipo: CRUDa
#### Integrantes
+ Fernando Isaac González Medina
+ Julio César Jiménez Bolio
+ Mauricio Andrés Miramontes Ramírez
+ Víctor Manuel Sánchez Sánchez

### Misión


### Visión


### Historial de versiones
|   Fecha  | Versión |             Descripción            |                                  Autores                                 |
|:--------:|:-------:|:----------------------------------:|:----------------------------------------------------------------------:|
|  |  0.0.0  |  |  Fernando González, Julio Jiménez, Mauricio Miramontes, Víctor Sánchez |

## Definición del problema


## Diagrama general de casos de uso


## Descripción del proyecto
El proyecto es un sistema de blog simple que utiliza el lenguaje PHP y el servidor web Apache. Para la base de datos utiliza SQLite.

## Lista de características / funciones
+ Iniciar sesión
+ Cerrar sesión
+ Agregar comentario
+ Mostrar publicación
+ Crear publicación
+ Editar publicación
+ Eliminar publicación
+ Lista de publicaciones
+ Eliminar comentario
+ Restricciones de clave foránea

### index.php
Las primeras líneas determinan la ruta del archivo a la base de datos, 
luego creamos un nuevo objeto PDO con new PDO(), 
que podemos usar para acceder a los datos. Luego usamos el método query() 
para ejecutar una declaración SQL que lee artículos de la tabla post.

Después de conectarse a la base de datos, 
la consulta se utiliza para obtener valores de las columnas para cada fila de la tabla, 
devolviendo el orden de las filas en orden de creación inversa, o más reciente primero.

Usamos $stmt->fetch() para leer la siguiente fila disponible, 
hasta que no queden más filas.
 
Las cadenas de texto se envuelven en la función htmlspecialchars(). 
La razón de esto es que, si la entrada del usuario (un título de blog o una publicación)
contiene corchetes angulares, podría romper el HTML utilizado en el diseño de la página y
podría permitir que un usuario inyecte JavaScript se ejecutaría 
en las computadoras de otras personas.

### init.sql
La instrucción CREATE TABLE especifica qué propiedades tiene una publicación:
+ id: un identificador único para ayudarnos a distinguir los publicaciones
+ título: el encabezado
+ cuerpo: el texto principal del artículo
+ user_id: qué usuario creó el artículo (lo usaremos más adelante)
+ fecha_creacion: cuando se creó el artículo
+ fecha_actualización: cuando se actualizó el artículo

Por lo tanto, una publicación del blog es solo una fila agregada a la tabla de publicaciones, ya que cada fila tiene espacio para almacenar todos estos valores.

### install.php
Se ejecuta cuando queramos configurar el blog (o borrarlo y comenzar de nuevo).

La primera mitad del código está escrito en PHP. Aquí, se usa una serie de declaraciones if () para asegurar que todo salga bien. La variable $error se establece en un mensaje de error, si algo sale mal. 
Los pasos que se toman son:
+ Asegurarse de que la base de datos no se haya creado en el archivo data.sqlite. Si es así, requerimos que el archivo se elimine manualmente, por lo que es más difícil sobrescribir accidentalmente los datos.
+ SQLite funcionará bien con solo un archivo vacío, por lo que creamos uno con touch() e informamos si hubo algún problema al hacer eso (como cuando no lo permite el sistema de archivos).
+ Se lee el script SQL al usar file_get_contents () e informa un error si no se puede encontrar el archivo.
+ Luego se intenta ejecutar los comandos SQL usando $pdo->exec() y se informa cualquier problema con esto.
+ Finalmente, se cuenta el número de filas de publicaciones que se han creado.

La segunda mitad del archivo (desde <!DOCTYPE html>) presenta los resultados del script en HTML.