APP = app
EXEC_APP = docker-compose exec -T $(APP)
COMPOSER = $(EXEC_APP) composer


##
## Help
## ----

help: ## List of all commands
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-24s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m## /[33m/' && printf "\n"

.DEFAULT_GOAL := help

.PHONY: help


##
## Docker commands
## ---------------

up: ## Up
	docker-compose up -d

build: ## Build or rebuild
	docker-compose up --build --force-recreate -d

down: ## Stop and remove
	docker-compose down

start: ## Start
	docker-compose start

stop: ## Stop
	docker-compose stop

logs: ## Show logs
	docker-compose logs --tail 20 -f

ps: ## List running containers
	docker ps \
	--format "table {{.ID}}\t{{.Names}}\t{{.Image}}\t{{.Ports}}\t{{.RunningFor}}\t{{.Status}}" \
	--filter "name=abuse" \
	--filter "name=rabbitmq"

no-docker: ## Run command outside docker container, e.g. `make no-docker phpcs`
	$(eval docker-compose := \#)
	$(eval EXEC_APP := )

.PHONY: up build down start stop logs ps no-docker

##
## Project commands
## ----------------

app: ## Abuse app container
	docker-compose exec $(APP) sh

app-root: ## Abuse app container via root user
	docker-compose exec -w /usr/local/app --user=root $(APP) sh

.PHONY: app app-root
