# Usa la imagen base de PHP con FPM
FROM php:8-fpm

# Instala dependencias
RUN apt-get update && \
   apt-get install -y apt-utils git unzip zip libzip-dev

# Instalatu sql kontsultak egiteko    
RUN docker-php-ext-install mysqli pdo pdo_mysql   

# Configura y habilita las extensiones de PHP
#RUN docker-php-ext-configure zip --with-libzip && \
    #docker-php-ext-enable mysqli zip


    

    

# Instala Composer
#RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
#    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
#    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
#    php -r "unlink('composer-setup.php');"

# Comando por defecto para ejecutar PHP-FPM
CMD ["php-fpm"]
