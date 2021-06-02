FROM php:7.4-fpm

MAINTAINER PaulaGawargious

ARG UID=1000
ARG UNAME=notification_Adastra_one
ARG LARAVEL_ENV

RUN useradd --comment "Docker user" --create-home --shell /bin/bash --uid 1000 --user-group $UNAME \
    && usermod --lock $UNAME

ENV MAIN_PACKAGES="default-mysql-client nodejs tini yarn" \
    PHP_DEPENDENCIES="libfreetype6 libjpeg62-turbo libmbedcrypto3 libmbedtls12 libmbedx509-0 \
    libpng16-16 libwebp6 libx11-6 libx11-data libxau6 libxcb1 libxdmcp6 libxpm4 libxslt1.1 libzip4 unzip" \
    PHP_BUILD_DEPENDENCIES="bzip2-doc cmake cmake-data icu-devtools libarchive13 libbz2-dev \
    libevent-2.1-6 libexpat1 libffi-dev libfreetype6-dev libgmp-dev libgmpxx4ldbl libgnutls-dane0 \
    libgnutls-openssl27 libgnutls28-dev libgnutlsxx28 libicu-dev libidn2-dev libjpeg62-turbo-dev \
    libjsoncpp1 libmbedtls-dev libp11-kit-dev libpng-dev libpng-tools libprocps7 \
    libpthread-stubs0-dev librhash0 libssl-dev libtasn1-6-dev libtasn1-doc libunbound8 libuv1 \
    libwebp-dev libwebpdemux2 libwebpmux3 libx11-dev libxau-dev libxcb1-dev libxdmcp-dev \
    libxml2-dev libxpm-dev libxslt1-dev libzip-dev nettle-dev procps psmisc x11proto-core-dev \
    x11proto-dev xorg-sgml-doctools xtrans-dev zlib1g-dev"

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update -qq \
    && apt-get install -qqy --no-install-recommends $MAIN_PACKAGES $PHP_DEPENDENCIES $PHP_BUILD_DEPENDENCIES \
    && if [ "$LARAVEL_ENV" = "PRODUCTION" ]; then \
            mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
            ; \
        else \
            mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini \
            && pecl install xdebug && docker-php-ext-enable xdebug \
            && echo "xdebug.cli_color=2" >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini \
            && echo "xdebug.mode=off" >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini \
            ; \
        fi \
    && docker-php-ext-install bcmath bz2 calendar exif ffi gettext gd intl mysqli opcache pcntl \
                                pdo_mysql shmop sockets sysvmsg sysvsem sysvshm xsl zip \
    && pecl install redis && docker-php-ext-enable redis \
    && echo "memory_limit = 8192M" >> $PHP_INI_DIR/conf.d/docker-php-ext-memory-limit.ini \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer --version=2.0.9 \
    && php -r "unlink('composer-setup.php');" \
    && apt-get purge -qqy $PHP_BUILD_DEPENDENCIES \
    && apt-get autoremove -qqy && apt-get autoclean -qqy \
    && apt-get remove -qqy && apt-get clean -qqy \
    && rm -rf /var/lib/apt/lists/* /tmp/pear





WORKDIR /app

RUN chown -R $UNAME:$UNAME /app

USER $UNAME



COPY --chown=$UNAME:$UNAME ["composer.json", "./"]

COPY --chown=$UNAME:$UNAME [".env.example", ".env", "./"]

RUN composer install --no-autoloader --no-cache --no-scripts --prefer-dist --quiet $([ "$LARAVEL_ENV" = "PRODUCTION" ] && echo "--no-dev")

COPY --chown=$UNAME:$UNAME [".", "."]

RUN composer dump-autoload --optimize

