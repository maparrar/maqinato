Maqinato v.0.2 (pre-alpha)
¡¡¡No es funcional. Solo disponible para pruebas hasta la versión 1.0.!!!

Casi-Framework para desarrollo de aplicaciones web y backend para aplicaciones móviles. 
Más que un framework es una forma de organizar un proyecto de PHP basado en varias
ideas de frameworks comerciales.

Con suerte ofrecerá una API accesible para aplicaciones web nativas, web móviles y nativas móviles.


REQUISITOS
==========
- Activar mod_rewrite en apache


INTERNACIONALIZACIÓN Y LOCALIZACIÓN
==========
Si se quiere usar la localización "l10n" e internacionalización "i18n" se debe
instalar gettext usando el administrador de paquetes de la distribución, en varias
ya viene instalado, sino en Debian/Ubuntu:
```
#> apt-get install gettext
```
- Un "locale" es una lista de parámetros que definen el idioma, la localización
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