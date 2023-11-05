# Variables
DOCKER_COMPOSE = docker-compose

build:
	@$(DOCKER_COMPOSE) build

remove:
	@$(DOCKER_COMPOSE) down -v

start:
	@$(DOCKER_COMPOSE) up -d
