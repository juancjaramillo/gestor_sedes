# Changelog

All notable changes to this project will be documented in this file.  
This project adheres to [Semantic Versioning](https://semver.org/) and uses **Conventional Commits**.

## [0.1.0] - 2025-08-08
### Added
- Backend (Laravel 12) con API REST `/api/v1/locations` (GET paginado + POST crear).
- Autenticación por API Key (middleware alias en `bootstrap/app.php`) y rate limiting por API Key.
- Manejo de errores JSON unificado para `/api/*` con códigos (`E_INVALID_PARAM`, `E_UNAUTHORIZED`, etc.).
- Capa Service/Repository y Resource (DTO).
- Cache de listados (TTL configurable).
- Base de datos SQLite (migración + seeder ≥10 registros, idempotente).
- Frontend (React + TypeScript) con MUI, Axios (API Key), filtros, paginación y formulario con Zod.
- Linters: Laravel Pint; análisis estático: PHPStan. ESLint/Prettier en frontend.
- Tests: PHPUnit (backend) y Jest/RTL (frontend).
- CI en GitHub Actions para lint + tests en backend y frontend.
- README con requisitos, comandos, API Key y rutas versionadas, y cómo correr los tests.
