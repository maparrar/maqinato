Maqinato v.0.5.0 (pre-alpha)
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

Cambios
==========
Versión 0.5.0:
----------
- Controller para signup, login y logout
- Inclusión de scripts con import
- Nueva versión de debug
- Estilos básicos y MediaQueries en los CSS
- Responsive design
- Carga de configuración desde el servidor
- Refresco de sesión automático

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

Versión 0.3.0:
----------
- Soporte para i18n y l10n