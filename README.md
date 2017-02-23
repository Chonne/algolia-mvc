# Algolia MVC example

## To do

### backend

- put the view stuff in a Response class? It'll basically take care of setting the http response code and echoing a string or requiring a template with params, so I'm not sure it's necessary. It would make the POC more "MVC" though.

### frontend

- make it prettier
- remove a result's row from the list after removing it
- add message area for (usually successful) responses
- add keyboard navigation for the results
- close addform when pressing "esc", if the textarea wasn't changed, otherwise ask for confirmation
- results: display more than 5?
- add extra info on the page, such as a link to this github, to Algolia's website, and to whatever else comes to mind

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
