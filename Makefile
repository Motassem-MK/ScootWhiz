SAIL=./vendor/bin/sail
PROJECT_NAME=$(notdir $(patsubst %/,%,$(CURDIR)))

setup: # Sets up the app contains and installs dependencies
	make install-dependencies
	make build
	make start
	sleep 10 # MySQL needs some time before it can accept connections
	make migrate

install-dependencies:
	cp .env.example .env
	docker run --rm \
          -u "$$(id -u):$$(id -g)" \
          -v "$$(pwd):/var/www/html" \
          -w /var/www/html \
          laravelsail/php82-composer:latest \
          composer install --ignore-platform-reqs

build: # Builds the app
	$(SAIL) build

start: # Starts the app
	$(SAIL) up -d

stop: # Stops the app
	$(SAIL) down

remove: # Removes all project-related containers and volumes
	make stop
	docker ps -aqf "name=$(PROJECT_NAME)_*" | xargs docker rm
	docker volume rm $$(docker volume ls -qf "name=$(PROJECT_NAME)*")

test: # Runs all tests
	$(SAIL) artisan test

migrate: # Migrates the database
	$(SAIL) artisan migrate:fresh

demo: # Starts the demo
	$(SAIL) artisan demo:start

phpcs: # Runs PHP CodeSniffer
	$(SAIL) composer phpcs

phpstan: # Runs PHPStan
	$(SAIL) composer phpstan

