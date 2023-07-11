############################################################
# Dockerfile to build CentOS,Nginx installed  Container
# Based on CentOS
############################################################

# Set the base image to Ubuntu
FROM centos:8
USER root
#clADD . /opt

RUN cd /etc/yum.repos.d/ && alias ll='ls -l'
RUN sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-*
RUN sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*

# Installing nginx
RUN dnf -y install centos-release-stream && \
dnf -y swap centos-{linux,stream}-repos && \
dnf -y distro-sync
RUN dnf -y install curl nginx php php-cli php-common php-gd php-json php-pdo php-xml php-zip python3 python3-pip python3-setuptools \
&& python3 -m pip install supervisor

RUN mkdir /run/php-fpm && chown apache:apache /run/php-fpm && chmod 777 /run/php-fpm

COPY composer.json /opt/

# Adding the configuration file of the nginx
# ADD .installer/.docker/nginx.conf /etc/nginx/nginx.conf
ADD .installer/.docker/supervisord.conf /etc/supervisord.conf



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer && cd /opt/ && /usr/local/bin/composer install && /usr/local/bin/composer install-mvc
EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisord.conf", "-n"]
