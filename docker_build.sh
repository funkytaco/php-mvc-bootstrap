#!/bin/sh
echo Building Docker image.
docker build -t php-mvc-apache .
echo Running Docker container...
docker run -v `pwd`:/var/www -p 80:80 php-mvc-apache
