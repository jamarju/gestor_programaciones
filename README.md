# Gestión de programaciones didácticas

Aplicación para facilitar la gestión de programaciones didácticas en IES.

![screenshot](img/demo-01.png)

## Pre-requisitos

* Apache/Nginx + PHP

* Librería ZipArchive:

```
sudo apt-get install php7.0-zip
```

## Instalación

Bajar dependencias con composer, ejecutar desde la carpeta `gp`:

```
cd gp
composer update
```

Crear virtualhost de Apache/Nging apuntando su raíz a `gp/public`.

Cambiar propietario de `gp`:

```
chown -R www-data:www-data gp
```

Aumentar el tamaño máximo de upload/post. Ejemplo para nginx + php7.0-fpm:

`/etc/php/7.0/fpm/php.ini`:

```
upload_max_filesize = 50M
post_max_size = 50M
```

`/etc/nginx/sites-available/default` -> 

```
server {
	...
	client_max_body_size 50M;
}
```

## Configuración

Las tablas de departamentos, asignaturas y niveles por asignatura están en `gp/data/asignaturas.ods`.