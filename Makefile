# === Novactive Collection Helpers ===

# Styles
YELLOW=$(shell echo "\033[00;33m")
RED=$(shell echo "\033[00;31m")
RESTORE=$(shell echo "\033[0m")

# Variables
PHP_BIN := php
COMPOSER_BIN := composer
DOCKER_BIN := docker
SRCS := src
CURRENT_DIR := $(shell pwd)

.PHONY: list
list:
	@echo "*********************"
	@echo "${YELLOW}Available targets${RESTORE}:"
	@echo "*********************"
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-15s[0m %s\n", $$1, $$2}'


.PHONY: codeclean
codeclean:
	@$(PHP_BIN) vendor/bin/phpmd src text .cs/md_ruleset.xml
	@$(PHP_BIN) vendor/bin/phpmd tests text .cs/md_ruleset.xml
	@$(PHP_BIN) vendor/bin/php-cs-fixer fix --config=.cs/.php_cs.php
	@$(PHP_BIN) vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php src
	@$(PHP_BIN) vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php tests

.PHONY: tests
tests:
	@bash $(CURRENT_DIR)/tests/runtests.bash

.PHONY: install
install:
	@$(COMPOSER_BIN) install

.PHONY: docmethods
docmethods:
	@$(PHP7_BIN) tests/gendocmethods.php

.PHONY: coverage
coverage:
	$(DOCKER_BIN) run -t --rm -w /app -v $(CURRENT_DIR):/app phpunit/phpunit --coverage-html /app/tests/coverage

.PHONY: clean
clean:
	rm -rf tests/coverage
	rm .php_cs.cache
	rm -rf vendor


