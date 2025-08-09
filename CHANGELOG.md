# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and uses Conventional Commits.

## [0.1.0] - 2025-08-08
### Added
- Backend (Laravel 12) con API REST `/api/v1/locations` (GET paginado + POST crear).
- Autenticación por API Key (middleware alias en `bootstrap/app.php`) y rate limiting por API Key (`throttle:api-key`).
- Manejo de errores JSON unificado para `/api/*` con códigos (`E_INVALID_PARAM`, `E_UNAUTHORIZED`, etc.).
- Capa Service/Repository y Resource (DTO).
- Cache de listados (TTL 30s).
- Base de datos SQLite (migración + seeder 10+ registros).
- Frontend (React + TypeScript) con MUI, Axios (API Key), filtros, paginación y formulario con Zod.
- Linters: Laravel Pint, ESLint/Prettier; análisis estático: PHPStan.
- Tests iniciales (PHPUnit + Jest/RTL).
- CI en GitHub Actions para lint + tests (backend y frontend).
- README con instrucciones completas y checklist.

### Pending
- Ampliar cobertura de pruebas a ≥ 80% (backend y frontend).
- (Opcional) Docker Compose más robusto (nginx + php-fpm); por ahora se incluye una versión dev simple.
