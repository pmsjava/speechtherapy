# Logopedia (Symfony 5 + Flex, PHP 8)

## Uruchomienie (Docker)
1. `docker-compose up -d --build`
2. W kontenerze php: `composer install`
3. Klucze JWT: `php bin/console lexik:jwt:generate-keypair`
4. Migracje: `php bin/console doctrine:migrations:migrate`
5. (Opcjonalnie) Fixtures: `php bin/console doctrine:fixtures:load -n`
6. Aplikacja: http://localhost:8080
7. Swagger UI: http://localhost:8080/api/doc

## API (JWT)
- `POST /api/login` (JSON `{ "username": "admin", "password": "adminpass" }`) → token
- Używaj `Authorization: Bearer <token>`
- CRUD:
  - `GET /api/appointments?page=1&perPage=10&from=2025-08-01&to=2025-08-31&q=Kowalski`
  - `GET /api/appointments/{id}`
  - `POST /api/appointments`
  - `PUT /api/appointments/{id}`
  - `DELETE /api/appointments/{id}`

## Rate limiting
- Login: 5 prób / minutę
- API: 100 req / minutę (global)

## Testy
- Unit/functional: `vendor/bin/phpunit`
- E2E (Panther): `PANTHER_NO_HEADLESS=1 vendor/bin/phpunit tests/E2E`

## Deploy (AWS ECS/Fargate)
- Skonfiguruj ECR, ECS Cluster/Service.
- Uzupełnij `infra/ecs-taskdef.json`.
- W GitHub Secrets ustaw: `AWS_ROLE_ARN`, `AWS_REGION`, `ECR_PHP_REPO`, `ECR_NGINX_REPO`, `ECS_CLUSTER`, `ECS_SERVICE`.
- Push na `main` zainicjuje workflow.
