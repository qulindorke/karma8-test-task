DOCKER_COMPOSE = docker-compose --env-file=.env

build:
	@$(DOCKER_COMPOSE) build

remove:
	@$(DOCKER_COMPOSE) down -v

start:
	@$(DOCKER_COMPOSE) up -d

check-subscriptions:
	@$(DOCKER_COMPOSE) run --rm scheduler php run check-subscriptions
