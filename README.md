# Gestor de Sedes

[![CI Backend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml/badge.svg)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/backend.yml)
[![CI Frontend](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml/badge.svg)](https://github.com/juancjaramillo/gestor_sedes/actions/workflows/frontend.yml)

Aplicación CRUD para gestionar **sedes** (Laravel + React + Vite + MUI). Este README te guía para levantar el proyecto **desde cero**, correr tests/lint, y entender el **CI** (GitHub Actions).

---

## 1) Requisitos

- **PHP 8.3** y **Composer**
- **Node.js 20.x** y **npm**
- **SQLite 3** (recomendado para desarrollo/CI) o MySQL
- Git

> En Windows, usa *PowerShell*; en Linux/Mac, la *terminal*.

---

## 2) Clonar e instalar dependencias

```bash
git clone https://github.com/juancjaramillo/gestor_sedes.git
cd gestor_sedes
```

### Backend
```bash
cd backend
composer install
```

### Frontend
```bash
cd ../frontend
npm ci
```

---

## 3) Configurar el Backend (Laravel)

Desde `gestor_sedes/backend`:

1. **Variables de entorno**  
   - Crea tu archivo `.env`:
     ```bash
     cp .env.example .env    # Linux/Mac
     # PowerShell (Windows):
     copy .env.example .env
     ```

2. **APP_KEY (obligatorio)**  
   ```bash
   php artisan key:generate
   ```

3. **Base de datos (SQLite recomendado)**  
   - Crea el archivo de base de datos:
     ```bash
     # Linux/Mac
     mkdir -p database && touch database/database.sqlite

     # PowerShell (Windows)
     New-Item -ItemType Directory -Force -Path .\database | Out-Null
     New-Item -ItemType File -Force -Path .\database\database.sqlite | Out-Null
     ```
   - En `.env`, deja:
     ```dotenv
     DB_CONNECTION=sqlite
     DB_DATABASE=database/database.sqlite
     ```

   > Si prefieres MySQL, ajusta `DB_CONNECTION=mysql`, host, usuario, clave y nombre de BD.

4. **Migraciones y Seeders**  
   Crea/estructura tablas y carga datos de prueba:
   ```bash
   php artisan migrate --seed
   # Si quieres limpiar y recrear todo:
   # php artisan migrate:fresh --seed
   ```

5. **(Opcional) Storage symlink** si subes archivos:
   ```bash
   php artisan storage:link
   ```

6. **Levantar API**  
   ```bash
   php artisan serve
   # http://127.0.0.1:8000
   ```

### Seguridad por API Key (si aplica)
Si tu middleware exige API key, agrega en `backend/.env` una clave (ejemplo):
```dotenv
API_KEY=dev-123456
```
y envía ese valor desde el frontend como header (típicamente `X-API-KEY: dev-123456`). Ajusta el nombre del header según tu middleware.

---

## 4) Configurar el Frontend (React + Vite)

Desde `gestor_sedes/frontend`:

1. **Variables de entorno**  
   ```bash
   cp .env.example .env      # Linux/Mac
   # PowerShell (Windows)
   copy .env.example .env
   ```
   En `frontend/.env` define al menos:
   ```dotenv
   VITE_API_BASE_URL=http://127.0.0.1:8000
   # Si usas API Key:
   # VITE_API_KEY=dev-123456
   ```

2. **Alias `@`**  
   El alias `@` apunta a `frontend/src`. Ya está definido en `tsconfig.json` y usado por Vite.

3. **Correr en desarrollo**
   ```bash
   npm run dev
   # http://localhost:5173
   ```

4. **Build de producción**
   ```bash
   npm run build
   npm run preview
   ```

---

## 5) Tests y Lint

### Backend (PHPUnit)
```bash
cd backend
php artisan test
```

### Frontend (Jest + RTL) y ESLint
```bash
cd frontend
npm test -- --watch=false
npm run lint
```

> Nota: Los tests de React usan `jsdom` y `@testing-library/*`. Las advertencias de `ts-jest` sobre `isolatedModules` son esperadas: ya está configurado en `tsconfig.jest.json`.

---

## 6) Endpoints principales (API)

- `GET /api/locations` — Lista paginada.
- `POST /api/locations` — Crea una sede.
- `PUT /api/locations/{id}` — Actualiza una sede.
- `DELETE /api/locations/{id}` — Elimina una sede.

Ejemplo rápido con `curl` (crear):
```bash
curl -X POST http://127.0.0.1:8000/api/locations   -H "Content-Type: application/json"   -H "X-API-KEY: dev-123456" \ 
  -d '{"name":"Sede Norte","address":"Calle 1 #23-45","phone":"+57 300 123 4567"}'
```

---

## 7) Integración Continua (GitHub Actions)

Los workflows viven en `.github/workflows/` y se ejecutan en **push** y **pull requests**.

- **Backend:** `.github/workflows/backend.yml`  
  Instala PHP y Composer, prepara `.env`, ejecuta migraciones + seeders y corre tests.

- **Frontend:** `.github/workflows/frontend.yml`  
  Instala Node 20, corre `lint`, `test` y `build`. Sube el `dist/` como artifact.

Badges al inicio del README apuntan a estos workflows.

> Si tienes un `ci.yml` viejo, **elimínalo** para evitar duplicados:
> ```bash
> git rm .github/workflows/ci.yml
> git commit -m "ci: remove legacy ci.yml"
> git push
> ```

---

## 8) Problemas comunes (Troubleshooting)

- **CRLF/LF en Git (Windows):** El repo incluye `.gitattributes` para normalizar saltos de línea. Si ves advertencias, puedes re-normalizar:
  ```bash
  git add --renormalize .
  git commit -m "chore(git): normalize line endings"
  ```

- **Alias `@` no resuelto:** Verifica `paths` en `frontend/tsconfig.json` y reinicia Vite.

- **Node incorrecto:** Este repo pide **Node 20.x** (`"engines"` en `package.json`). En GitHub Actions los workflows ya usan 20.x.

- **Jest: “not wrapped in act(...)”:** En tests simulamos `act()` y filtramos ese warning con el `console.error` spy en `src/test/setup.ts`.

---

## 9) Scripts útiles

### Backend
```bash
php artisan migrate:fresh --seed
php artisan tinker
php artisan route:list
```

### Frontend
```bash
npm run dev
npm run build
npm run preview
npm run lint
npm test -- --watch=false
```

---

## 10) Licencia

licencia MIT 

---


