# Gestor de Sedes

[![CI Backend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml/badge.svg?branch=main)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml)
[![CI Frontend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml/badge.svg?branch=main)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml)

Aplicación full‑stack para gestionar sedes (Laravel + React/Vite + TypeScript). Incluye tests de backend con PHPUnit y de frontend con Jest + React Testing Library, además de pipelines de CI en GitHub Actions.

---

## Requisitos

- **PHP 8.2+** (recomendado 8.3) con Composer
- **Node 20.x** y npm
- **SQLite** (para desarrollo y CI) o **MySQL/PostgreSQL** si prefieres
- Git

> El proyecto está preparado para **SQLite** por defecto (más simple para desarrollo y CI).

---

## Estructura

```
backend/     # Laravel API
frontend/    # Vite + React + TypeScript
.github/workflows/
  ├─ backend.yml   # CI del backend
  └─ frontend.yml  # CI del frontend
```

---

## Setup rápido (todo)

```bash
git clone https://github.com/juancjaramillo/gestor_sedes.git
cd gestor_sedes
```

### 1) Backend (Laravel)

```bash
cd backend

# 1) Instalar dependencias
composer install

# 2) Configurar entorno
cp .env.example .env
php artisan key:generate

# 3) Base de datos (SQLite recomendado)
mkdir -p database
# Linux/macOS:
touch database/database.sqlite
# Windows PowerShell:
# New-Item -ItemType File -Path database\database.sqlite | Out-Null

# 4) Ajustar .env para SQLite
# En .env asegúrate de tener:
# DB_CONNECTION=sqlite
# DB_DATABASE=absolute/path/a/backend/database/database.sqlite
# (en Windows usa rutas con backslashes)

# 5) Migraciones y seed
php artisan migrate
php artisan db:seed

# 6) Levantar el backend
php artisan serve
# => http://127.0.0.1:8000
```

> Si estás usando MySQL/PostgreSQL cambia `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` en `.env` y omite la parte de SQLite.

#### Ejecutar tests de backend
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
# VITE_API_BASE_URL=http://127.0.0.1:8000

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

## Poblar datos (migraciones + seeder)

- En **local**:
  ```bash
  cd backend
  php artisan migrate
  php artisan db:seed
  ```

- En **CI** (GitHub Actions) ya está automatizado:
  - Se ejecuta `migrate --force` y `db:seed --force` contra SQLite.

---

## CI (GitHub Actions)

Este repo incluye dos workflows:

- **CI Backend**: `/.github/workflows/backend.yml`  
  - Instala PHP y Composer  
  - Prepara SQLite y `.env`  
  - Ejecuta migraciones + seed  
  - Corre `php artisan test`

- **CI Frontend**: `/.github/workflows/frontend.yml`  
  - Instala Node 20.x  
  - `npm ci`, `npm run lint`, `npm test`, `npm run build`

Los *badges* del README apuntan a `main`. Asegúrate de tener los archivos `backend.yml` y `frontend.yml` en tu rama `main` para que aparezcan con estado.

---

## Solución de problemas

- **Badges “no status”**: confirma que existen `backend.yml` y `frontend.yml` en `main` y que el nombre de archivo coincide con el badge. Haz al menos un push para disparar los workflows.
- **`Unknown option "--no-interaction"`** en CI del backend: usa `php artisan test` o `php artisan -n test`. (Este README y el workflow ya lo usan sin ese flag.)
- **`EBADENGINE Node 20.x`**: instala Node **20.x** localmente o usa `nvm`/`nvm-windows` para fijar versión.
- **Rutas CORS/URL API**: ajusta `VITE_API_BASE_URL` en `frontend/.env` para que apunte a tu backend.

---

## Scripts útiles

**Backend**  
- `php artisan migrate:fresh --seed` – recrea tablas y re‑siembra
- `php artisan storage:link` – enlaza almacenamiento público

**Frontend**  
- `npm run dev` – servidor de desarrollo Vite  
- `npm run build` – build de producción  
- `npm run lint` – ESLint  
- `npm test -- --watch=false` – tests una sola vez

---

## Licencia

MIT
