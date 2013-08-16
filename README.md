Maqinato v.0.2 (pre-alpha)
¡¡¡No es funcional. Solo disponible para pruebas hasta la versión 1.0.!!!

Casi-Framework para desarrollo de aplicaciones web y backend para aplicaciones móviles. 
Más que un framework es una forma de organizar un proyecto de PHP basado en varias
ideas de frameworks comerciales.

Con suerte ofrecerá una API accesible para aplicaciones web nativas, web móviles y nativas móviles.


REQUISITOS
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