# Proyecto INGSOFTWARE

Este es un proyecto colaborativo desarrollado en PHP y MySQL para gestionar [descripción breve del proyecto].

## Requisitos
- PHP 7.4 o superior
- MySQL (phpMyAdmin recomendado)
- Servidor web (Apache, Nginx o el servidor integrado de PHP)
- Git

## Configuración del Proyecto

### 1. Clonar el Repositorio
Clona el repositorio en tu máquina local:



### 2. Configurar la Base de Datos
1. Abre phpMyAdmin (o tu cliente MySQL favorito).
2. Crea una base de datos llamada `login_system`.
3. Importa el archivo `sql/login_system.sql` desde phpMyAdmin. 

### 3. Configurar las Credenciales
1. Copia el archivo `config.example.php` a `config.php`:ç
Edita `db_connect.php` con tus credenciales locales (host, usuario, contraseña, nombre de la base de datos).




### 5. Acceder al Proyecto
Abre tu navegador y visita `http://localhost:8000` para ver el proyecto.

## Estructura del Proyecto
- `db_connect.php`: Archivo de conexión a la base de datos.
- `sql/login_system.sql`: Estructura de la base de datos.
- `config.example.php`: Ejemplo de archivo de configuración.
- `uploads/`: Carpeta para archivos subidos (ignorada en Git).

### 4. Iniciar el Servidor
Inicia un servidor PHP en el directorio del proyecto:

## Notas
- Si realizas cambios en la estructura de la base de datos, actualiza el archivo `sql/login_system.sql` y avisa a los demás.
- No subas el archivo `config.php` ni la carpeta `uploads/` al repositorio.