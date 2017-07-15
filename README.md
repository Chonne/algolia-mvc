# Algolia MVC example

This is a sample app including an autocomplete input and a simple form to add entities to an Algolia index. The PHP backend takes care of adding and removing entities.

This was done for a recruitment test, which I've failed, mainly because of the backend architecture's not being "MVC" enough and because it couldn't be easily tested (eg lousy dependency injection). The version submitted was 1.0.0. Time spent: 17h.

Most of the issues will be fixed, just for the sake of doing it properly. No libs will be used, unless really needed (eg phpunit). This will still be a POC and isn't meant to be used in production. If that had been the case, I would've used existing libraries and a basic framework such as Silex (no need to reinvent the wheel).

## Demo

You can try it out here: https://algolia-mvc.shaun.fr/

## Requirements

- [Composer](https://getcomposer.org/download/)
- php >= 5.4
- Web server (I've been using Apache)

## Install instructions

```
# clone the repo
git clone https://github.com/Chonne/algolia-mvc .

# get composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install

# Add your Algolia credentials to the local config file `config/parameters.php`
cp config/parameters.php.dist config/parameters.php

```

### Server configuration examples

#### Virtual host

Apache virtualhost configuration example, highly inspired by Symfony's default htaccess. It also still contains the original comments. The paths are the default ones in a Debian based docker image:

```
# 100-algolia-mvc.conf
<VirtualHost *:80>
        DocumentRoot /var/www/html/web/
        ErrorLog /var/log/apache2/algolia-mvc.fr-error_log
        CustomLog /var/log/apache2/algolia-mvc.fr-access_log combined
        DirectoryIndex index.php

        <Directory "/var/www/html">
                AllowOverride None
                Require all denied
        </Directory>

        <Directory "/var/www/html/web">
                Options -Indexes
                Require all granted

                FallbackResource /index.php
        </Directory>
</VirtualHost>
```

If you're using a managed host that doesn't allow you to set your own virtual hosts, make sure your subdomain's root is the `web` folder and that it supports `.htaccess` files.


#### Dockerfile

Note that a virtualhost conf file should be available in the same folder for this to work.

See the docker compose file to launch it.

```
FROM php:5.6.30-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip

RUN usermod -u 1000 www-data

COPY 100-algolia-mvc.conf /etc/apache2/sites-available/100-algolia-mvc.conf
RUN ln -s /etc/apache2/sites-available/100-algolia-mvc.conf /etc/apache2/sites-enabled/100-algolia-mvc.conf \
    && rm /etc/apache2/sites-enabled/000-default.conf

RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer
```

#### Docker compose

Not really necessary, as there's only one container, but allows anyone to configure the port and volume the way they like:

```
version: '2'
services:
  php-apache:
    build: ./php-apache
    image: php-apache

    # These values could also be in a docker-compose.override.yml file to ease sharing
    ports:
      - '50080:80'
    volumes:
      - ${HOME}/Projects/github-algolia-mvc:/var/www/html
      # Uncomment this if you want composer's cache to remain in your host
      # - ${HOME}/Projects/github-algolia-mvc/_composer:/var/www/.composer
```

To access the container as www-data and run local commands such as `php composer.phar`:

`docker exec --user www-data -i -t containers_php-apache_1 /bin/bash`

## To do

These are ideas I've had to improve the project, that either should've been done for the test itself or as general improvements because I tend to be a perfectionist.

### general

- document the Rest API (RAML? Swagger?)
- create a docker container with the code?
- include docker and htaccess files to ease installation

### backend

- unit tests
- better error handling, with proper exceptions and error codes (4xx instead of 5xx if it's the user's fault)
- validate json with schemas? Will require using an external lib
- use a Router class?
- build a Request class that will be passed to the controller and will prepare the parameters
- use a config parameter with the site's base url to allow usage in a subdirectory
- in debug mode: return more details in case of errors

### frontend

- a bit more responsiveness
- addform could have both the current raw version for quick copy/pasting and a new multi inputs one
- add keyboard navigation for the results
- include fetch polyfill using npm?
- if more libs are used, perhaps use something like gulp to build stuff
- use scss?
- close addform when pressing "esc", if the textarea wasn't changed, otherwise ask for confirmation
- results: display more than 5?
- show the total number of results? Would be helpful when there aren't any, but not only
- divide the js code into separate modules
- use babel and newer javascript?
