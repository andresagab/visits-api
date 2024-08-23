# Visits API

## Descripción

Este proyecto es una API para gestionar visitas, construida con Laravel 11.x, Vue3, TailwindCSS, Inertia, Sanctum, Ziggy, Laravel Prompts, Swagger-php y L5-Swagger. La aplicación proporciona una API RESTful para manejar registros de visitas y utiliza autenticación con tokens.

## Requisitos

- PHP 8.1 o superior
- Composer
- Node.js y npm
- MySQL

## Clonar repositorio y ejecutar entorno de desarrollo

1. **Crear el proyecto Laravel**

   ```bash
   git clone https://github.com/andresagab/visits-api.git
   ```

2. **Navegar al directorio del proyecto**
    
    ```bash
   cd ./visits-api
   ```

3. Copiar archivo .env.example
    
    ```bash
   cp .env-example .env
   ```

4. Configurar archivo .env

    - Asigna las credenciales de base de datos:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=visits_api
    DB_USERNAME=root
    DB_PASSWORD=
   ```

5. Instala las dependecias de Composer:
   ```bash
   composer install
   ``` 
6. Instala las dependencias de npm
    ```bash
   npm install
   ```
7. Genera la llave del proyecto:
    ```bash
   php artisan key:generate
   ```
8. Ejecuta las migraciones:
    ```bash
   php artisan migrate
   ```
9. Ejecuta los seeders para datos de prueba:
    ```bash
   php artisan db:seed
   ```
10. Ejecuta el servidor de desarrollo
    ```bash
    php artisan serve
    ```
11. Compilar assets
    ```bash
    npm run dev
    ```

## Documentación de la api

Puedes acceder a la documentación de la api por medio de la url de tu servidor en la ruta: `/api/documentation`

1. Generar documentación

    ```bash
    php artisan l5-swagger:generate
    ```

> Recuerda actualizar los controladores para gestionar la documentación de la API:
> - `/app/Http/Controllers/API/LoginController`
> - `/app/Http/Controllers/API/V1/VisitController`

## Frontend

Puedes acceder al frontend de la aplicación web por medio de la url de tu servidor en la ruta: `/` o `/login`

- Para acceder a la ruta `/visits` debes estár autenticado.
  - Para autenticarte completa el formulario de LogIn con un usuario registrado previamente.
    - Recuerda que el seeder registra 10 usuarios de prueba.
- La ruta `/visits` te permite visualizar las visitas registradas en un mapa, además, los registros se muestran paginados.

## Crear una nueva visita (PROMPT)

La aplicación cuenta con un comando artisan para crear registros de visitas:

1. En la terminal ejecuta el comando:
    ```sh
   php artisan visit:create
   ```
2. Completa el formulario ingresando el `nombre`, `email`, `latitud` y `longitud` de la visita'
