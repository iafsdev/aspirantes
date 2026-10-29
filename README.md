# Selección de Aspirantes

Aplicación web para gestionar un proceso de selección de personal. Permite:

- **Registrar aspirantes**: nombre completo, puesto al que aplica, estado civil, fecha de nacimiento y carrera o formación académica. Sus datos se pueden corregir después desde el detalle.
- **Evaluar a cada aspirante** en conocimientos técnicos, referencias y habilidades blandas (de 0 a 10, con hasta dos decimales), junto con comentarios generales. La evaluación se puede editar después.
- **Consultar** el listado de aspirantes (paginado, con búsqueda por nombre o puesto y su promedio) y el detalle de cada uno con su evaluación.

Desarrollada con Laravel 13, PHP 8.5 y MySQL 8.4. Las decisiones técnicas están en [`docs/DECISIONES.md`](docs/DECISIONES.md).

## Requisitos

- [Docker](https://docs.docker.com/get-docker/) con **Docker Compose v2** (incluido en Docker Desktop).
- El puerto **8080** libre.

No se necesita PHP, Composer ni MySQL instalados en la máquina.

## Arranque

Desde la raíz del proyecto:

```bash
docker compose up --build
```

Al iniciar, el contenedor genera la clave de la aplicación, crea las tablas en MySQL y arranca el servidor. Cuando aparezca `resuming normal operations`, abre:

**http://localhost:8080**

La primera ejecución tarda unos minutos porque descarga las imágenes. Para correrlo en segundo plano, agrega `-d`.

### Credenciales de la base de datos (opcional)

Por defecto funciona sin configurar nada. Para usar credenciales propias, define estas variables en el entorno (o en un archivo `.env` en la raíz) antes de arrancar:

| Variable | Valor por defecto |
|---|---|
| `MYSQL_DATABASE` | `aspirantes` |
| `MYSQL_USER` | `aspirantes` |
| `MYSQL_PASSWORD` | `aspirantes` |
| `MYSQL_ROOT_PASSWORD` | `root` |

## Detener, reiniciar y datos de ejemplo

```bash
# Detener (los datos se conservan en el volumen de MySQL)
docker compose down

# Reiniciar desde cero (borra la base de datos)
docker compose down -v && docker compose up --build

# Cargar datos de ejemplo (con la aplicación en ejecución)
docker compose exec app php artisan db:seed --class=DatosEjemploSeeder --force

# Ver los registros de la aplicación
docker compose logs -f app
```

El seeder crea cuatro aspirantes, tres de ellos evaluados. No se ejecuta en el arranque; solo cuando se pide con el comando anterior.

## Pruebas

Las pruebas de feature usan SQLite en memoria, por lo que no requieren MySQL ni la aplicación en ejecución.

**Con Docker** (sin instalar nada más):

```bash
docker run --rm -v "$PWD":/app -w /app composer:2 sh -c \
  "composer install --ignore-platform-req=ext-bcmath && cp -n .env.example .env && php artisan test"
```

**Con PHP 8.3+ y Composer locales**:

```bash
composer install
cp .env.example .env
php artisan test
```

Cubren: alta y edición válidas e inválidas, evaluación válida y fuera de rango (`-1`, `10.5`, texto, más de dos decimales), edición, regla de una evaluación por aspirante, listado con búsqueda y paginación, detalle, página 404 y escape de HTML.

## Estructura del proyecto

```
app/
├── Enums/EstadoCivil.php              Catálogo cerrado de estados civiles
├── Http/
│   ├── Controllers/
│   │   ├── AspiranteController.php    Listado, alta, detalle y edición
│   │   └── EvaluacionController.php   Crear y editar la evaluación
│   └── Requests/
│       ├── StoreAspiranteRequest.php  Validación del alta y la edición
│       └── StoreEvaluacionRequest.php Validación de calificaciones
└── Models/
    ├── Aspirante.php                  Relación hasOne con Evaluacion
    └── Evaluacion.php                 Relación belongsTo y promedio calculado
database/
├── migrations/                        Tablas aspirantes y evaluaciones
├── factories/                         Datos falsos para las pruebas
└── seeders/DatosEjemploSeeder.php     Datos de ejemplo (ejecución manual)
resources/views/
├── layouts/app.blade.php              Plantilla base (Bootstrap)
├── aspirantes/                        index, create, edit, show y formulario compartido
├── evaluaciones/                      create, edit y formulario compartido
├── partials/calendario.blade.php      Selector de fecha dd/mm/aaaa
└── errors/                            Páginas 404, 419 y 500 en español
routes/web.php                         Rutas de la aplicación
lang/es/                               Mensajes de validación en español
public/css/app.css                     Color institucional sobre Bootstrap
tests/Feature/                         Pruebas de feature
docker/
├── apache.conf                        DocumentRoot en public/
└── entrypoint.sh                      Clave, migraciones y arranque
Dockerfile                             Imagen multietapa (Composer → PHP 8.5 + Apache)
compose.yaml                           Servicios app y db (MySQL 8.4)
docs/DECISIONES.md                     Tecnologías, supuestos y decisiones técnicas
```
