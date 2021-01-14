isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
domain := "dev.silvain.eu"
user := $(shell id -u)
group := $(shell id -g)


ifeq ($(isDocker), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	de := docker-compose exec
	dr := $(dc) run --rm
	sy := $(de) php bin/console
	node := $(dr) node
	php := $(dr) --no-deps php
else
	sy := php bin/console
	node :=
	php :=
endif

OSFLAG 				:=
ifeq ($(OS),Windows_NT)
	OSFLAG += win
else
	UNAME := $(shell uname -r)

	ifeq ($(findstring Microsoft,$(UNAME)),Microsoft)
		OSFLAG = win
	else
		OSFLAG = linux
	endif
endif

ifeq ($(OSFLAG),win)
	dockerRun := docker run -v $(PWD):/app -w /app --rm -it -e "TERM=xterm-256color" 172.31.1.120:5000/intersysteme/helpdesk_php:latest
else
	dockerRun :=
endif

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: dev
dev: vendor/autoload.php node_modules ## Lance le serveur de développement
	$(dc) up

.PHONY: lint
lint: vendor/autoload.php ## Analyse le code
	$(dockerRun) ./vendor/bin/phpstan analyse  --memory-limit=-1

.PHONY: lintb
lintb: vendor/autoload.php ## Analyse le code (sans docker)
	./vendor/bin/phpstan analyse  --memory-limit=-1

.PHONY: format
format: ## Formate le code
	$(dockerRun) ./vendor/bin/phpcbf
	$(dockerRun) ./vendor/bin/php-cs-fixer fix --allow-risky=yes --config ".php_cs.dist"

.PHONY: test
test: ## Lancer les tests unitaire
	$(dockerRun) ./bin/phpunit


# -----------------------------------
# Dépendances
# -----------------------------------
vendor/autoload.php: composer.lock
	$(php) composer install
	touch vendor/autoload.php

node_modules/time: yarn.lock
	$(node) yarn
	touch node_modules/time

public/assets: node_modules/time
	$(node) yarn run build

public/assets/manifest.json: package.json
	$(node) yarn
	$(node) yarn run build