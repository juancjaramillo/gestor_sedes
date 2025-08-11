# Gestor de Sedes

[![CI Backend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml/badge.svg?branch=main)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml)
[![CI Frontend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml/badge.svg?branch=main)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml)

Aplicación **full‑stack** para gestionar sedes (**Laravel 12 + PHP 8.2/8.3** y **React/Vite + TypeScript**). Incluye:

* API REST con Laravel
* Subida de imágenes a `storage/public` + `storage:link`
* Validaciones con FormRequests
* Tests: **PHPUnit** (backend) y **Jest + Testing Library** (frontend)
* **GitHub Actions** para CI (backend y frontend)

---

## Requisitos

* **PHP 8.2+** (recomendado 8.3) y **Composer**
* **Node 20.x** y **npm**
* **SQLite** (por defecto para dev y CI) o **MySQL/PostgreSQL**
* **Git**

> El proyecto está listo para **SQLite** por defecto (más simple para desarrollo y CI).

---

## Estructura del repositorio

```
backend/     # Laravel API
frontend/    # Vite + React + TypeScript
.github/workflows/
  ├─ backend.yml   # CI del backend
  └─ frontend.yml  # CI del frontend
```

---

## Arranque rápido

```bash
git clone https://github.com/juancjaramillo/gestor_sedes.git
cd gestor_sedes
```

### 1) Backend (Laravel)

```bash
cd backend

# 1) Instalar dependencias
composer install

# 2) Configurar entorno local
cp .env.example .env
php artisan key:generate

# 3) Base de datos SQLite (recomendada)
mkdir -p database
# Linux/macOS:
touch database/database.sqlite
# Windows PowerShell:
# New-Item -ItemType File -Path database\database.sqlite | Out-Null

# 4) Ajustar .env para SQLite (ver sección de Variables de entorno)

# 5) Migraciones + seed
php artisan migrate
php artisan db:seed

# 6) (opcional) Enlazar storage público
php artisan storage:link

# 7) Levantar el backend
php artisan serve
# => http://127.0.0.1:8000
```

> Si usan MySQL/PostgreSQL, cambia `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` en `.env` y omita la parte de SQLite.

#### Tests del backend

```bash
php artisan test
```

### 2) Frontend (Vite + React + TS)

En otra terminal:

```bash
cd frontend

# 1) Instalar dependencias
npm ci  # o npm install

# 2) Variables de entorno del frontend
cp .env.example .env
# Abre .env y ajusta la URL de la API si hace falta, por ejemplo:
# VITE_API_BASE_URL=http://127.0.0.1:8000/api
# VITE_API_KEY=<mismo API_KEY del backend>

# 3) Desarrollo
npm run dev
# => http://localhost:5173

# 4) Linter y tests
npm run lint
npm test -- --watch=false

# 5) Build de producción
npm run build
```

---

## Variables de entorno

### Backend `.env` (local)

* Genera la clave: `php artisan key:generate`
* Si usas SQLite, deja algo así:

```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Clave generada por artisan
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

FILESYSTEM_DISK=public
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# API (usada por el middleware x-api-key)
API_KEY=sk_local_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
API_RATE_LIMIT=300
API_CACHE_TTL=0
```

### Backend `.env.testing` (PHPUnit y CI)

> Este archivo lo usa PHPUnit y el workflow de GitHub Actions.

```dotenv
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost
APP_KEY=base64:GENERADA_POR_ARTISAN

DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_STORE=array
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
SESSION_DRIVER=array
MAIL_MAILER=array

API_KEY=sk_local_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
API_RATE_LIMIT=2
API_CACHE_TTL=1
```

Genera la clave de testing (opcional en local; en CI se genera en el workflow):

```bash
php artisan key:generate --env=testing
```

### Frontend `.env`

```dotenv
VITE_API_BASE_URL=http://127.0.0.1:8000/api
VITE_API_KEY=<mismo API_KEY del backend>
```

**Cabecera de autenticación para la API**

```
x-api-key: <valor de API_KEY>
```

---

## Poblar datos (migraciones + seeder)

* En **local**:

```bash
cd backend
php artisan migrate
php artisan db:seed
```

* En **CI** (GitHub Actions) ya está automatizado.

---

## API rápida

**Base**: `http://127.0.0.1:8000/api/v1`

**Rutas**

* `GET /locations` – lista paginada (`?page=1&per_page=10&name=&code=`)
* `POST /locations` – crea (`code`, `name`, `image?`)
* `GET /locations/{id}` – detalle
* `PUT /locations/{id}` – actualiza (acepta `multipart/form-data`; desde el frontend se envía `POST` + `_method=PUT`)
* `DELETE /locations/{id}` – elimina

**Validaciones**

* `code`: requerido, único, `max:50`
* `name`: requerido, `max:255`
* `image`: opcional (`jpg,jpeg,png,webp,gif`, máx 2MB)

**Ejemplos `curl`**

```bash
# Listado
curl -H "x-api-key: $API_KEY" http://127.0.0.1:8000/api/v1/locations

# Crear
curl -X POST -H "x-api-key: $API_KEY" \
  -F code=BOG -F name=Bogotá \
  -F image=@/ruta/a/imagen.jpg \
  http://127.0.0.1:8000/api/v1/locations

# Actualizar (multipart + _method=PUT)
curl -X POST -H "x-api-key: $API_KEY" \
  -F _method=PUT -F code=BOG -F name="Bogotá D.C." \
  http://127.0.0.1:8000/api/v1/locations/1

# Eliminar
curl -X DELETE -H "x-api-key: $API_KEY" \
  http://127.0.0.1:8000/api/v1/locations/1
```

---

## CI (GitHub Actions)

### Backend (`.github/workflows/backend.yml`)

Incluye (entre otros) estos pasos claves:

```yaml
- name: Copy env testing
  run: cp .env.testing.example .env.testing || true

- name: Generate app key (testing)
  run: php artisan key:generate --env=testing

- name: Run tests
  run: php artisan test --env=testing
```

> El proyecto usa **SQLite en memoria** para pruebas (`DB_DATABASE=:memory:`). No hace falta crear archivo.

### Frontend (`.github/workflows/frontend.yml`)

Pasos típicos:

```yaml
- uses: actions/setup-node@v4
  with:
    node-version: 20
- run: npm ci
- run: npm run lint
- run: npm test -- --watch=false
- run: npm run build
```

Los *badges* del README apuntan a `main`. Asegúrate de tener estos archivos en esa rama para ver el estado.

---

## Calidad y mantenimiento (backend)

```bash
# Chequeo de estilo
composer lint
# Arreglo automático
composer lint-fix
# Análisis estático
composer phpstan

# Limpieza de caché (útil si cambias .env o rutas)
php artisan config:clear
php artisan route:clear
```

---

## Scripts útiles

**Backend**

* `php artisan migrate:fresh --seed` – recrea tablas y reinstala el seed
* `php artisan storage:link` – enlaza almacenamiento público

**Frontend**

* `npm run dev` – servidor de desarrollo Vite
* `npm run build` – build de producción
* `npm run lint` – ESLint
* `npm test -- --watch=false` – tests una sola vez

---



## Licencia

MIT
