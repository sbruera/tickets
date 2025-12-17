# Technical Dev Test

## Setup

1. Copy environment file:
```bash
cp .env.example .env
```

2. Start Docker containers:
```bash
docker compose up -d
```

3. Install dependencies:
```bash
docker compose exec app composer install
npm install
```

4. Generate application key:
```bash
docker compose exec app php artisan key:generate
```

5. Run migrations and seeders:
```bash
docker compose exec app php artisan migrate --seed
```

6. Build assets:
```bash
npm run build
```

## Access

- Application: http://localhost:8000
- Database: localhost:3307
  - User: `laravel` / Password: `laravel_password`
  - Root: `root` / Password: `root_password`

## Test Accounts

- **User**: test@example.com / password
- **Agent**: agent@example.com / password

## Troubleshooting

### Permission Issues

If you encounter permission errors, fix them with:

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

