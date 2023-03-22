ARG PROJECT_PHP_VERSION
FROM php:${PROJECT_PHP_VERSION}-fpm

ARG PROJECT_PHP_VERSION

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev; \
    # cleanup
    rm -rf /var/lib/apt/lists/* \
    #####################################
    # Composer:
    #####################################
    && curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer --version=2.2.6

RUN set -eux; \
     # Install the PHP pdo_mysql extention
    docker-php-ext-install pdo_mysql; \
    # Install the PHP pdo_pgsql extention
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    docker-php-ext-install exif; \
    php -r 'var_dump(gd_info());'

# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      gnupg2 \
      git \
      libzip-dev zip unzip && \
      docker-php-ext-configure zip; \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'

#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------

###########################################################################
# Mysqli Modifications:
###########################################################################

RUN docker-php-ext-install mysqli

###########################################################################
# Image optimizers:
###########################################################################

USER root

RUN apt-get install -yqq jpegoptim optipng pngquant gifsicle

###########################################################################
# ImageMagick:
###########################################################################

USER root

RUN apt-get install -yqq libmagickwand-dev imagemagick && \
    cd /tmp && \
    git clone https://github.com/Imagick/imagick; \
    cd imagick && \
          phpize && \
          ./configure && \
          make && \
          make install && \
          rm -r /tmp/imagick; \
    docker-php-ext-enable imagick; \
    php -m | grep -q 'imagick'

###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PROJECT_PHP_VERSION}."

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

ENV LC_ALL "POSIX"

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
