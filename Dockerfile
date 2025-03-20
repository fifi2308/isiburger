# Utiliser une image de base PHP avec Apache
FROM php:8.4-apache

# Installer les extensions PHP nécessaires pour Laravel
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zlib1g-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql

# Copier le code de ton projet dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail pour le serveur Apache
WORKDIR /var/www/html

# Exposer le port 80
EXPOSE 80

# Lancer Apache en mode foreground
CMD ["apache2-foreground"]
