PHP ?= php
COMPOSER ?= composer
PEST := vendor/bin/pest
PHPUNIT_CONFIG := phpunit.xml.dist

.PHONY: install test test-filter

install:
	$(COMPOSER) install --no-interaction

test: vendor/autoload.php
	$(PHP) $(PEST) --configuration=$(PHPUNIT_CONFIG)

test-filter: vendor/autoload.php
	$(PHP) $(PEST) --configuration=$(PHPUNIT_CONFIG) --filter="$(filter)"

vendor/autoload.php: composer.json
	$(MAKE) install
