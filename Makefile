#!make

mode?=development

# --------------------------------------------------------------------------
# Clean
# --------------------------------------------------------------------------
# Using git tool, remove all ignored files/directories from project and
# restore to original version
#
clean:
	@echo "Cleaning project";
	@git clean -dfX;

# --------------------------------------------------------------------------
# Setup
# --------------------------------------------------------------------------
# Create environment file
#
.setup-env-file:
	@( \
		if [ ! -f ".env" ]; then \
			cp ".env.example" ".env"; \
			echo "File .env was created successfully in root project."; \
		fi; \
		if [ "$(mode)" = "testing" ] && [ ! -f ".env.testing" ]; then \
			cp ".env.testing.example" ".env.testing"; \
			echo "File .env.testing was created successfully in root project."; \
		fi; \
	);

.setup-application-key:
	@( \
		php artisan key:generate; \
		if [ "$(mode)" = "testing" ]; then \
			php artisan key:generate --env=testing; \
		fi; \
	)

.setup-artisan-optimize:
	@( \
		if [ "$(mode)" = "production" ]; then \
			php artisan optimize; \
		fi; \
	)

# --------------------------------------------------------------------------
# Test
# --------------------------------------------------------------------------
# Run all tests defined in <./test> directory
#
# @link: https://phpunit.readthedocs.io/es/latest/index.html
# @link: https://laravel.com/docs/5.8/testing
#
test:
	@make .setup-env-file mode=testing;
	@./composer exec -- phpunit --verbose --stop-on-failure --testdox;



# --------------------------------------------------------------------------
# Test Debug
# --------------------------------------------------------------------------
# Run all tests defined in <./test> directory and enable debug output
#
# @link: https://phpunit.readthedocs.io/es/latest/index.html
#
test-debug:
	@make .setup-env-file mode=testing;
	@( \
		export XDEBUG_MODE=debug,gcstats,trace; \
		export XDEBUG_SESSION=VSCODE; \
		env; \
		./composer exec -- phpunit --verbose --debug --stop-on-failure --testdox; \
	);



# --------------------------------------------------------------------------
# Coverage
# --------------------------------------------------------------------------
# Allow generate a coverage report
#
# @link: https://phpunit.readthedocs.io/es/latest/index.html
# @link: https://laravel.com/docs/5.8/testing
#
coverage:
	@make .setup-env-file mode=testing;
	@( \
		export XDEBUG_MODE=coverage; \
		export XDEBUG_SESSION=VSCODE; \
		./composer exec -- phpunit --verbose --stop-on-failure --coverage-text; \
	);

# --------------------------------------------------------------------------
# Coverage (report in HTML)
# --------------------------------------------------------------------------
# Extends of "test" makefile command
# Allow generate a coverage code report in html format.
# Fore more information plase see https://phpunit.readthedocs.io/es/latest/code-coverage-analysis.html
#
coverage-html:
	@make .setup-env-file mode=testing;
	@( \
		export XDEBUG_MODE=coverage; \
		export XDEBUG_SESSION=VSCODE; \
		./composer exec -- phpunit --verbose --stop-on-failure --coverage-html=storage/framework/testing/coverage-report; \
	);

# --------------------------------------------------------------------------
# Fixer
# --------------------------------------------------------------------------
# Format code using php-cs-fixer tool
# The rules are defined in ".php_cs"
# For more information please see https://github.com/FriendsOfPHP/PHP-CS-Fixer
#
cs:
	@./composer exec -- php-cs-fixer fix --dry-run --diff;

# --------------------------------------------------------------------------
# Fixer
# --------------------------------------------------------------------------
# Format code using php-cs-fixer tool
# The rules are defined in ".php_cs"
# For more information please see https://github.com/FriendsOfPHP/PHP-CS-Fixer
#
cs-fixer:
	@./composer exec -- php-cs-fixer --verbose fix --show-progress=dots --diff;

# --------------------------------------------------------------------------
# Serve
# --------------------------------------------------------------------------
# Typically, you may use a web server such as Apache or Nginx to serve your
# Laravel applications. If you are on PHP 5.4+ and would like to use PHP's
# built-in development server.
#
serve:
	@( \
		if [ "$(mode)" = "production" ]; then \
			php artisan octane:install --server=swoole; \
			php artisan octane:start --workers=4 --task-workers=6 --host=0.0.0.0 --port=8000; \
		else \
			export XDEBUG_MODE=debug,gcstats,trace; \
			export XDEBUG_SESSION=VSCODE; \
			php artisan serve --host=0.0.0.0 --port=8000; \
		fi; \
	);

# --------------------------------------------------------------------------
# Tinker
# --------------------------------------------------------------------------
# Start a new session of Laravel tinker
# More info https://github.com/laravel/tinker
#
tinker:
	@php -d pcre.jit=0 artisan tinker;

# --------------------------------------------------------------------------
# Routes
# --------------------------------------------------------------------------
# Generate a list of routes defined in Laravel routes folder
#
route:
	@php artisan route:list;




# --------------------------------------------------------------------------
# Migrate
# --------------------------------------------------------------------------
# Run the migration tool to install the last version of database schema
# More info https://laravel.com/docs/8.x/migrations
#
db-migrate:
	@echo "Running database migrations.";
	@php artisan migrate --force --no-interaction;

# --------------------------------------------------------------------------
# Fresh migration
# --------------------------------------------------------------------------
# Run the migration tool in mode fresh
# More info https://laravel.com/docs/5.8/migrations
#
db-fresh:
	@echo "Running database migrations with seeders.";
	@php artisan migrate:fresh --seed;

# --------------------------------------------------------------------------
# Composer tools
# --------------------------------------------------------------------------
.composer-clean:
	@./composer clearcache;

.composer-dump:
	@./composer dump-autoload;

.composer-install:
	@./composer install --optimize-autoloader --no-interaction;

.composer-install-production:
	@./composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader --classmap-authoritative;

.composer:
	@( \
		if [ "$(mode)" = "production" ]; then \
			make .composer-install-production; \
		else \
			make .composer-install; \
		fi; \
	);
	@make .composer-dump;


# --------------------------------------------------------------------------
# Install
# --------------------------------------------------------------------------
# Install all packages and setup
#
install: .composer .setup-env-file .setup-application-key .setup-artisan-optimize
	@echo;
	@echo "A few steps more:";
	@echo "    - Please consider configure the environment file (.env.) first.";
	@echo "    - You can run database migrations and seeders with 'make db-fresh'";
	@echo "    - For start the built-in development server, run 'make run'.";
	@echo "    - For start local workers server, run 'make worker'.";
	@echo "    - For debug, install xdebug extension in your computer and copy the .infra/php/php-ext-xdebug.ini file in PHP extension path.";
	@echo "    - For testing, you can run 'make test'.";
	@echo "    - You can send all local installation to docker container with 'make deploy'.";
	@echo;

reinstall: clean install

deploy: .clean-containers .setup-env-file
	@( \
		echo "Deploy in $(mode) mode"; \
		if [ "$(mode)" = "testing" ]; then \
			docker-compose --project-name="$$APP_NAME" -f .deploy/docker/docker-compose.test.yml up --detach --build; \
		else \
			if [ "$(mode)" = "development" ]; then \
				source .env; \
				docker-compose \
					--env-file .env \
					--project-name="$$APP_NAME" \
					-f .deploy/docker/docker-compose.local.yml \
					-f .deploy/docker/docker-compose.database.yml \
					up --detach --build; \
			else \
				source .env; \
				docker-compose \
					--env-file .env \
					--project-name="$$APP_NAME" \
					--file .deploy/docker/docker-compose.yml \
					up --detach --build; \
			fi; \
		fi; \
	);


.clean-containers:
	@( \
		CONTAINER_TEST="Test-Integration"; \
		CONTAINER_API="API-Server"; \
		CONTAINER_WORKER="Worker-Server"; \
		CONTAINER_SCHEDULER="Scheduler-Server"; \
		CONTAINER_DATABASE="Database-Server"; \
		CONTAINER_STORE="Store-Server"; \
		CONTAINER_PROXY="Proxy-Server"; \
		if [ "$(mode)" = "testing" ]; then \
			bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_TEST"; \
		else \
			if [ "$(mode)" = "development" ]; then \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_API"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_WORKER"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_SCHEDULER"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_STORE"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_DATABASE"; \
			else \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_PROXY"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_API"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_WORKER"; \
				bash ./.deploy/bash/safe-remove.docker.sh "$$CONTAINER_SCHEDULER"; \
			fi; \
		fi; \
	);

.prune-deploy:
	@( \
		source .env; \
		app_name="$${APP_NAME// /}"; \
		app_name=$$(tr '[:upper:]' '[:lower:]' <<< "$${app_name}"); \
		docker system prune --all --force ||:; \
		docker volume rm "$${app_name}_volume" ||:; \
	);
