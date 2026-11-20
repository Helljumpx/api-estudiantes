# API de Estudiantes - Laravel + Docker

## Requisitos previos
- Docker y Docker Compose instalados.

## Pasos de ejecucion
1. Clonar el repositorio o descomprimir el archivo `.zip`.
2. Copiar el archivo de entorno (si no existe `.env`):
   `cp .env.example .env`
3. Verificar que en `.env` esten configurados:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=db`
   - `DB_PORT=3306`
   - `DB_DATABASE=estudiantes_db`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=root`
4. Levantar los contenedores:
   `docker compose up -d --build`
5. Ejecutar migraciones y seeders:
   `docker compose exec app php artisan migrate --seed`
6. Probar la API en `http://localhost:8000/api/health`.

## Por que DB_HOST debe ser `db` y no `localhost`?
En Docker, cada contenedor tiene su propia interfaz de red aislada. Si Laravel intenta conectarse a `localhost` (o `127.0.0.1`), buscara el servidor MySQL dentro del mismo contenedor `app`, donde no existe ninguna base de datos instalada, provocando un error de conexion rechazada.

Al usar Docker Compose, todos los servicios comparten una red interna con un servidor DNS integrado que resuelve el nombre de cada servicio hacia su IP interna. Por eso, al colocar `DB_HOST=db`, el contenedor `app` dirige correctamente las consultas al contenedor `db` donde corre MySQL.