# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-08-08
### Added
- CI para **backend** (Laravel): lint con Pint, análisis estático con PHPStan y ejecución de tests con PHPUnit.
- CI para **frontend** (React/Vite): ESLint, Jest y build de producción; publicación de `dist` como artifact.
- Limpieza explícita de cachés (`config:clear`, `route:clear`, `cache:clear`) en el pipeline de backend para evitar resultados inconsistentes.
- Variables de entorno seguras vía `secrets` para VITE_API_BASE_URL y API keys de prueba.

### Changed
- Normalización de `.env.testing` para usar SQLite en memoria, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `FILESYSTEM_DISK=public`.
- Ajustes menores en controladores/servicios/repositorio para compatibilidad con tests y estático.

### Fixed
- Validación de `code` única al actualizar (`Rule::unique()->ignore()`).
- Borrado seguro de imágenes antiguas en updates/delete.
- Factory de `Location` y tests de API con rate-limit y API key.

## [1.0.0] - 2025-08-10
### Added
- Backend en **Laravel 12** con endpoints CRUD:
  - `GET /api/v1/locations`
  - `POST /api/v1/locations`
  - `GET /api/v1/locations/{id}`
  - `PUT /api/v1/locations/{id}`
  - `DELETE /api/v1/locations/{id}`
- Subida de imagen a `storage/app/public/locations` y exposición vía `storage:link`.
- Middleware de API Key con rate limit por clave y cache de listados.
- Frontend en **React + Vite + MUI** con listados, filtros, creación y edición (multipart con `_method=PUT`).
- Tests:
  - Backend: Unit/Feature (PHPUnit), Pint, PHPStan.
  - Frontend: Jest + RTL, ESLint.
