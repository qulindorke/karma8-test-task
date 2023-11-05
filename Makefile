DOCKER_COMPOSE = docker-compose --env-file=.env

build:
	@$(DOCKER_COMPOSE) build

remove:
	@$(DOCKER_COMPOSE) down -v

start:
	@$(DOCKER_COMPOSE) up -d

init:
	@echo "Importing dev database dump..."
	@while ! docker-compose exec mysql mysqladmin ping -h "localhost" --silent; do \
			sleep 3; \
			echo "Waiting for MySQL..."; \
	done
	@$(DOCKER_COMPOSE) exec mysql sh -c 'mysql -u "$$MYSQL_USER" -p"$$MYSQL_PASSWORD" "$$MYSQL_DATABASE" < /dev-data/database-schema.sql'
