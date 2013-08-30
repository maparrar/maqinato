Maqinato v.0.6.1 (alpha)
==========
¡¡¡No es funcional. Solo disponible para pruebas hasta la versión 1.0.!!!

No-Framework para desarrollo de aplicaciones web y backend para aplicaciones móviles. 
Más que un framework es una forma de organizar un proyecto de PHP basado en varias
ideas de frameworks comerciales.

Está basado (no estrictamente) en los patrones de diseño de software MVA 
(Model-View-Adapter) y MVP (Model-View-Presenter)

Modelo
----------
El enfoque del modelo es Orientado a Objetos, es decir que la mayoría de la lógica
de la aplicación está definida en el Modelo. El Modelo también contiene la capa
de acceso a datos DAL.

Vista
----------
Contiene la interfaz gráfica de la aplicación, está compuesto por los contenidos
HTML, los estilos CSS y la funcionalidad en el cliente con Javascript.

Controladores (Presenter)
----------
Sirve de mediador entre el Modelo y la Vista. Sanitiza las entradas y salidas.
Funciona como una API para el Modelo.

En el futuro ofrecerá una API accesible para aplicaciones web nativas, web móviles y nativas móviles.

Características:
==========
- Usa PDO para la interfaz con la base de datos, evitando SQLInjection
- Hay una clase Crypt que codifica passwords con un algoritmo digestivo y con demora intencional para evitar ataques de fuerza bruta.
- Hace include dinámico de Clases, así no hay que poner [include directorio/NombreDeClase.php] cuando se necesita una clase, el solo las incluye
- Usa ModRewrite para definir como único punto de Request el index.php de la raíz del proyecto
- Permite la creación de URL's amigables, lo que beneficia si se necesitan estrategias de SEO. Las URL son de la forma: www.miaplicacion.com/nombreControlador/nombreFuncion/parametro1/parametro2/.../parametroN
- Está centralizado el control de Request a la aplicación, de esa manera solo pueden existir las redirecciones definidas por uno mismo, en caso de un acceso a otra, redirige a la página de error
- Sistema de internacionalización con gettext [https://www.gnu.org/software/gettext/], para hacer sitios "traducibles", por ahora solo disponible en PHP, en el futuro la idea es que funcione en JS.
- Manejo de varios ambientes para no tener que reconfigurar cada que se pone en producción o en pruebas. Por ejemplo, se define el ambiente de desarrollo, el de pruebas y el de producción. Automáticamente se configura de acuerdo a la IP donde se esté ejecutando.
- Tiene un sistema que puede regenerar la ID de sesión cada X minutos con probabilidad 0.1, para "despistar al enemigo".
- Es fácil configurarlo para https.
- Soporta Ajax
- El acceso a la base de datos se puede hacer con cuatro diferentes usuarios, uno para leer, un para escribir, uno para borrar y uno para todas las operaciones con registros. Así, una función de la capa de acceso a datos, que sea de consultas, solo puede leer, no puede escribir ni borrar, lo que aumenta un tris la seguridad
- En este momento incluye el sistema de registro y login de usuarios.
- Tiene un sistema de demonios (como los demonios de UNIX) en JS, con Ajax, que permite la consulta al servidor cada X tiempo y evita montones de request aislados. Eso mejora el rendimiento cuando el sistema requiere consulta constante al servidor.
- Archivos de configuración para parametrizar la aplicación.
- Debug centralizado para PHP y JS, con tres niveles de detalle.
- Existe un proyecto [https://github.com/maparrar/maqinatools] que sirve para generar las clases y las clases de acceso a datos automáticamente a partir de un archivo .sql con la definición de las tablas (solo genera clases para las entidades con una PK). Crea la clase MyClass con los campos de la tabla y crea una clase DaoMyClass con el CRUD (con PDO) para acceder a la base de datos.

Requisitos
==========
- Activar mod_rewrite en apache


Internacionalización y Localización
==========
Instalar gettext y agrergar "locale"
----------
Si se quiere usar la localización "l10n" e internacionalización "i18n" se debe
instalar gettext usando el administrador de paquetes de la distribución, en varias
ya viene instalado, sino en Debian/Ubuntu:
```
#> apt-get install gettext
```
Un "locale" es una lista de parámetros que definen el idioma, la localización
entre otros. Para generar los .mo a partir de los .po se requieren los "locale" 
respectivos:
- Para ver la lista de "locale" agregados al sistema:
```
$> locale -a
```

- Para ver la lista de "locale" disponibles para agregar:
```
$> cat /usr/share/i18n/SUPPORTED
```

- Para agregar uno de la lista:
```
#> locale-gen de_DE
```

Usar poedit para crear las traducciones
----------
Poedit [http://www.poedit.net/] sirve para editar los archivos .po que contienen
las traducciones y convertirlos a binarios .mo para acceso rápido de la aplicación.
Se puede instalar de los repositorios de varias distribuciones:
```
#> apt-get install poedit
```
Al crear el catálogo .po se debe ubicar dentro de una carpeta con el nombre del 
"locale", la estructura de la aplicación con el ejemplo anterior sería:
```
application
 |___ locale
       |___ de_DE
             |___ LC_MESSAGES
                   |___ messages.po
```
Poedit genera automáticamente el .mo, quedando:
```
application
 |___ locale
       |___ de_DE
             |___ LC_MESSAGES
                   |___ messages.po
                   |___ messages.mo
```

Selección de locale
----------
Maqinato detecta la configuración de idioma del navegador y busca una traducción
que corresponda, si no la encuentra, usa el texto que tiene por defecto, es decir,
el usado en programación.

Si se quiere forzar un "locale", en /engine/config/app.php modificar la variable
"locale" por uno especídico, por ejemplo "pr_BR".

Uso
----------
Cualquier texto que se quiera traducir en el futuro por medio de gettext debe ser
de la forma:
```
gettext("Texto a traducir a diferentes idiomas");
```
para mayor facilidad:
```
_("Texto a traducir a diferentes idiomas");
```

Entornos (Environments)
==========
Se pueden configurar varios entornos para una aplicación. Cada entorno representa
una instalación de la aplicación, así se puede tener un entorno para desarrollo,
uno para release y otro para producción. Es posible tener tantos entornos como se
quieran.

Cada entorno cuenta con una lista de URL's para que Maqinato identifique
automáticamente en qué entorno está trabajando. Por ejemplo, para el entorno de 
desarrollo es normal tener la URL "localhost", pero si se quiere acceder a la
aplicación por medio de una LAN, se debería agregar la URL que identifica al
servidor en la red local, por ejemplo: "192.168.0.102".

Para un entorno de producción, la URL puede ser del tipo: "150.51.10.52", 
"aws.server.production.com" o "www.example.com".

El archivo de configuración de entornos se encuentra por defecto en:
```
/engine/config/environment.php
```


Bases de datos
==========
La configuración de las bases de datos se hace directamente dentro de cada Entorno.
En el archivo:
```
/engine/config/environment.php
```
se encuentra al inicio una variable llamada $database, esta se puede establecer
y pasar a todos los Entornos si todos comparten los mismos datos de conexión. Si
tienen diferentes usuarios y/o claves de acceso, basta con configurar cada uno por
separado agregando un array de conexión a cada Entorno.

Como un intento por aumentar la seguridad de la aplicación, cada base de datos
puede contar con más de una conexión, así es posible definir una conexión (usuario
y clave) que solo permita leer de la base de datos (read) que será usada por la 
Capa de Acceso a Datos (DAL Data Access Layer) en las operaciones que requieran 
lectura de la base de datos por parte de las funciones CRUD (Create, Read, 
Update, Delete). Definir una conexión para escritura (write), una para borrado
(delete) y una para todas las operaciones (all).

Para crear una conexión para cada operación, es necesario crear los usuarios y 
permisos en la base de datos (requiere permisos de root). Por ejemplo:
```
GRANT SELECT,INSERT,UPDATE,DELETE ON myDatabase.* TO 'myUser'@'localhost' IDENTIFIED BY 'myFirstPassword';
GRANT SELECT ON myDatabase.* TO 'myUserRead'@'localhost' IDENTIFIED BY 'mySecondPassword';
GRANT INSERT,UPDATE ON myDatabase.* TO 'myUserWrite'@'localhost' IDENTIFIED BY 'myThirdPassword';
GRANT DELETE ON myDatabase.* TO 'myUserDelete'@'localhost' IDENTIFIED BY 'myFourthPassword';
```
En este caso la configuración quedaría:
```
$database=array(
    "name" => "myDatabase",
    "driver" => "mysql",
    "persistent" => true,
    "host"=>"localhost",
    "connections" => array(
        array(
            "name"=>"read",
            "login" => "myUserRead",
            "password" => "mySecondPassword"
        ),
        array(
            "name"=>"write",
            "login" => "myUserWrite",
            "password" => "myThirdPassword"
        ),
        array(
            "name"=>"delete",
            "login" => "myUserDelete",
            "password" => "myFourthPassword"
        ),
        array(
            "name"=>"all",
            "login" => "myUser",
            "password" => "myFirstPassword"
        )
    )
);
```

Estructura de la base de datos
----------
La siguiente es la estructura de la base de datos para Mysql
```
CREATE TABLE Role (id int(10) NOT NULL AUTO_INCREMENT, name varchar(255), PRIMARY KEY (id));
CREATE TABLE `Session` (id int(10) NOT NULL AUTO_INCREMENT comment 'Identificador de la sesi\u00f3n', ini datetime NULL, end datetime NULL, state tinyint, ipIni varchar(18), ipEnd varchar(18), phpSession varchar(255), `user` int(10) NOT NULL, PRIMARY KEY (id));
CREATE TABLE `User` (id int(10) NOT NULL, email varchar(255), password varchar(255), salt varchar(255), role int(10) NOT NULL, PRIMARY KEY (id));
CREATE TABLE Person (id int(10) NOT NULL AUTO_INCREMENT, name varchar(100), lastname varchar(100), PRIMARY KEY (id));
ALTER TABLE `User` ADD INDEX FKUser198555 (id), ADD CONSTRAINT FKUser198555 FOREIGN KEY (id) REFERENCES Person (id);
ALTER TABLE `Session` ADD INDEX FKSession78200 (`user`), ADD CONSTRAINT FKSession78200 FOREIGN KEY (`user`) REFERENCES `User` (id);
ALTER TABLE `User` ADD INDEX FKUser708634 (role), ADD CONSTRAINT FKUser708634 FOREIGN KEY (role) REFERENCES Role (id);
```

Se debe tener al menos un rol inicial:
```
INSERT INTO Role VALUES(1,"Superuser");
INSERT INTO Role VALUES(2,"Admin");
INSERT INTO Role VALUES(3,"User");
```

Cambios
==========
Versión 0.6.1:
----------
- Carga de roles de usuario

Versión 0.6.0 (alpha):
----------
- Completo el sistema de login y signup
- Sistema de demonios disponible

Versión 0.5.0:
----------
- Controller para signup, login y logout
- Inclusión de scripts con import
- Nueva versión de debug
- Estilos básicos y MediaQueries en los CSS
- Responsive design
- Carga de configuración desde el servidor
- Refresco de sesión automático
- main es la página principal de acceso

Versión 0.4.0:
----------
- Clases para conexión a bases de datos
- Manejo de una base de datos para cada Environment
- Diferentes conexiones para cada base de datos: read, write, delete, all.
- Agrega seguridad al mantener accesos diferentes para cada operación

Versión 0.3.1:
----------
- Reorganización de funciones
- Mejoras en el Router
- Inclusión de la clase Environment
- Uso de Environment para almacenar las bases de datos

Versión 0.3.0 (pre-alpha):
----------
- Soporte para i18n y l10n