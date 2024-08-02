# Use the official PHP image as the base image
FROM php:8.2-apache

# Copy the PHP application code to the Apache web root
COPY . /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80
