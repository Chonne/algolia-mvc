# Algolia MVC example

This is a sample app including an autocomplete input and a simple form to add entities to an Algolia index. The PHP backend takes care of adding and removing entities.

This was done for a recruitment test, which I've failed, mainly because of the backend architecture's not being "MVC" enough. The version submitted was 1.0.0. Time spent: 17h.

Most of the issues will be fixed, just for the sake of doing it properly. No libs will be used, unless really needed (eg phpunit). This will still be a POC and isn't meant to be used in production. If that had been the case, I would've used existing libraries and a basic framework such as Silex (no need to reinvent the wheel).

## To do

### general

- include requirements in readme and composer (php version)
- document the Rest API (RAML? Swagger?)
- create a docker container with the code?

### backend

- divide controller in 2 parts: 1 for the main page, the other for the api
- entity handling should be done by the controller. Model will be a bit like doctrine's repositories or entity manager
- better error handling, with proper exceptions and error codes (4xx instead of 5xx if it's the user's fault)
- validate json with schemas? Will require using an external lib
- define routes in a separate file, which will be passed to Application, perhaps as a Router class (especially if it's expected to be tested)
- define dependencies and their injections in a separate file (eg Model expects an instance of the search client, the controller shouldn't have to know that)
- build a Request class that will be passed to the controller and will prepare the parameters
- build a Response class that will be sent back by the controller and will take care of headers and displaying the response. ob_* functions will be used by a template engine (?) to store the templates' results
- use a config parameter with the site's base url to allow usage in a subdirectory
- support a debug mode (set in the config), which would return more details in case of errors

### frontend

- add keyboard navigation for the results
- include fetch polyfill using npm?
- if more libs are used, perhaps use something like gulp to build stuff
- use scss?
- close addform when pressing "esc", if the textarea wasn't changed, otherwise ask for confirmation
- results: display more than 5?
- show the total number of results? Would be helpful when there aren't any, but not only
- divide the js code into separate modules
- use babel and newer javascript?

## Install instructions

- Launch `composer install`
- Copy `config/parameters.php.dist` to `config/parameters.php` and add your Algolia credentials

### Server configuration examples

#### Virtual host

Apache virtualhost configuration example, highly inspired by Symfony's default htaccess. It also still contains the original comments. The paths are the default ones in a Debian based docker image:

```
# 100-algolia-mvc.conf
<VirtualHost *:80>
        DocumentRoot /var/www/html/web/
        # LogLevel alert rewrite:trace3
        ErrorLog /var/log/apache2/algolia-mvc.fr-error_log
        CustomLog /var/log/apache2/algolia-mvc.fr-access_log combined
        DirectoryIndex index.php

        <Directory "/var/www/html">
                AllowOverride None
                Require all denied
        </Directory>

        SetEnv APP_ENV dev
        SetEnv APP_NAME AlgoliaMVC

        <Directory "/var/www/html/web">
                Options Indexes FollowSymLinks
                Require all granted

                # Contents copied from the main .htaccess and adapted
                RewriteEngine on

                # Determine the RewriteBase automatically and set it as environment variable.
                # If you are using Apache aliases to do mass virtual hosting or installed the
                # project in a subdirectory, the base path will be prepended to allow proper
                # resolution of the app.php file and to redirect to the correct URI. It will
                # work in environments without path prefix as well, providing a safe, one-size
                # fits all solution. But as you do not need it in this case, you can comment
                # the following 2 lines to eliminate the overhead.
                RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
                RewriteRule ^(.*) - [E=BASE:%1]

                # Sets the HTTP_AUTHORIZATION header removed by apache
                RewriteCond %{HTTP:Authorization} .
                RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

                # If the requested filename exists, simply serve it.
                # We only want to let Apache serve files and not directories.
                RewriteCond %{REQUEST_FILENAME} -f
                RewriteRule .? - [L]

                # Rewrite all other queries to the front controller.
                RewriteRule .? %{ENV:BASE}/index.php [L]
        </Directory>
</VirtualHost>
```

#### Dockerfile

All the libs aren't mandatory, it's just an example. Note that a virtualhost conf and php.ini files should be available in the same folder for this to work.

See the docker compose file to launch it.

```
FROM php:5.6.30-apache

RUN apt-get update && apt-get install -y \
    git \
    libpng-dev \
    php5-gd \
    unzip \
    zlib1g-dev \
    libicu-dev \
    g++

RUN docker-php-ext-configure \
    intl

RUN docker-php-ext-install \
    gd \
    intl

RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/

COPY 100-algolia-mvc.conf /etc/apache2/sites-available/100-algolia-mvc.conf
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load \
    && ln -s /etc/apache2/sites-available/100-algolia-mvc.conf /etc/apache2/sites-enabled/100-algolia-mvc.conf \
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

## Requirements

- [Composer](https://getcomposer.org/download/)
- Web server with rewrite capabilities
