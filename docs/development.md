# Development and Quality Tools

The project keeps its local and CI workflows in Composer and npm scripts so contributors run the same checks in every environment.

## Initial setup

```bash
composer setup
```

This installs locked PHP and JavaScript dependencies, creates `.env` when needed, generates the application key, runs migrations, and builds frontend assets. Configure the database values in `.env` before running setup when the default MySQL connection is not available.

## Local development

```bash
composer dev
```

Laravel's development command starts the application processes configured by the framework. Frontend assets can also be run independently with `npm run dev`.

## Focused commands

```bash
composer test         # Pest test suite
composer lint         # Apply Pint formatting
composer lint:check   # Check formatting without changing files
composer types:check  # PHPStan/Larastan analysis
npm run build         # Production frontend build
```

Run the complete local quality gate with:

```bash
composer check
```

CI calls `composer ci:check`, which delegates to the same quality gate. The GitHub Actions workflow uses SQLite for isolation while MySQL remains the documented application default.

The PHP checks run in a single process so the same commands also work in containers and restricted development environments that do not allow local worker sockets.
