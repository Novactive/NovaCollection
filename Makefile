# === Novactive Collection Helpers ===

# Styles
YELLOW=$(shell echo "\033[00;33m")
RED=$(shell echo "\033[00;31m")
RESTORE=$(shell echo "\033[0m")

# Variables
PHP_BIN := php
PHP7_BIN := ./php7
COMPOSER_BIN := composer.phar
DOCKER_BIN := docker
SRCS := src
CURRENT_DIR := $(shell pwd)
SCRIPS_DIR := $(CURRENT_DIR)/scripts

.PHONY: list
list:
	@echo "Available targets:"
	@echo ""
	@echo "  $(YELLOW)codeclean$(RESTORE)    > run the codechecker"
	@echo "  $(YELLOW)tests$(RESTORE)        > run the tests"
	@echo ""
	@echo "  $(YELLOW)coverage$(RESTORE)     > generate the code coverage"
	@echo ""
	@echo "  $(YELLOW)docmethods$(RESTORE)   > dump the list/doc for README.md about methods"
	@echo ""
	@echo "  $(YELLOW)install$(RESTORE)      > install vendors"
	@echo "  $(YELLOW)clean$(RESTORE)        > removes the vendors, caches and coverage"


.PHONY: codeclean
codeclean:
	bash $(SCRIPS_DIR)/codechecker.bash

.PHONY: tests
tests:
	bash $(SCRIPS_DIR)/runtests.bash

.PHONY: install
install:
	$(PHP_BIN) $(COMPOSER_BIN) install

.PHONY: docmethods
docmethods:
	$(PHP7_BIN) tests/gendocmethods.php

.PHONY: doc
doc:
	rm -rf $(CURRENT_DIR)/doc/_build
	docker run -it --rm \
	-v $(CURRENT_DIR):/project \
	-e SPHINX_DOC_ROOT=/project/doc \
	-e SPHINXPROJ="Novactive Collection" \
	-e BUILDDIR=/project/doc/_build \
	-e REQUIREMENTS_FILE=/project/doc/requirements.txt \
	-e SOURCEDIR=/project \
	sonodar/sphinx-build

.PHONY: coverage
coverage:
	$(DOCKER_BIN) run -t --rm -w /app -v $(CURRENT_DIR):/app phpunit/phpunit --coverage-html /app/tests/coverage

.PHONY: clean
clean:
	rm -rf tests/coverage
	rm .php_cs.cache
	rm -rf vendor


