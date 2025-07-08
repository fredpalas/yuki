current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
docker-dir := $(current-dir)docker/

.PHONY: onboard
onboard: | setup docker-start composer-install

.PHONY: setup
setup: | init
	@echo "🎉🎉 Setup complete!"

.PHONY: init
init:
	@echo "⚙️ Initializing repository"
	@docker compose pull
	@docker compose build
	@echo "👌 repository initialized!"
	@echo "";

.PHONY: docker-start
docker-start:
	@echo "🐳🚀 starting docker services"
	@docker compose up -d
	@echo "👌 docker services started!"
	@echo "";

.PHONY: docker-stop
docker-stop:
	@echo "🐳🛑 stopping docker services"
	@docker compose stop
	@echo "👌 docker services stopped!"
	@echo "";

.PHONY: docker-restart
docker-restart:
	@echo "🐳🔄 restarting docker services"
	@docker compose restart
	@echo "👌 docker services restarted!"
	@echo "";

.PHONY: phpunit
phpunit:
	@echo "🐘🧪 running phpunit tests"
	# check if test database is set up
	@docker compose exec -u www-data php-fpm bash -c "php bin/console doctrine:database:create --if-not-exists --env=test"
	@docker compose exec -u www-data php-fpm bash -c "php bin/phpunit --colors=always --testdox"
	@echo "👌 phpunit tests passed!"
	@echo "";

.PHONY: composer-install
composer-install:
	@echo "🐘📦 Installing composer dependencies"
	@docker compose exec -u www-data php-fpm bash -c "composer install --no-interaction"
	@echo "👌 dependencies installed!"
	@echo "";

.PHONY: phpstorm-coverage-folder
phpstorm-coverage-folder:
	@echo "🐘📦 creating coverage folder for phpstorm"
	@docker compose exec -u root php-fpm bash -c "mkdir -p /opt/phpstorm-coverage"
	@docker compose exec -u root php-fpm bash -c "chown -R www-data:www-data /opt/phpstorm-coverage"
	@echo "👌 coverage folder created!"
	@echo "";
