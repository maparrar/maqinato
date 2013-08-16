Maqinato v.0.3 (pre-alpha)
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

Cambios
==========
Versión 0.3:
----------
- Soporte para i18n y l10n