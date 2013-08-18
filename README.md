Maqinato v.0.4.0 (pre-alpha)
¡¡¡No es funcional. Solo disponible para pruebas hasta la versión 1.0.!!!

Casi-Framework para desarrollo de aplicaciones web y backend para aplicaciones móviles. 
Más que un framework es una forma de organizar un proyecto de PHP basado en varias
ideas de frameworks comerciales.

Con suerte ofrecerá una API accesible para aplicaciones web nativas, web móviles y nativas móviles.


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


Cambios
==========
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