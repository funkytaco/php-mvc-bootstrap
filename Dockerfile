############################################################
# Dockerfile to build CentOS,Nginx installed  Container
# Based on CentOS
############################################################

# Set the base image to Ubuntu
FROM centos:7
VOLUME /opt

# Add the ngix and PHP dependent repository
ADD .installer/.docker/nginx.repo /etc/yum.repos.d/nginx.repo

# Installing nginx
RUN yum -y install nginx && yum -y --enablerepo=remi,remi-php56 install php php-fpm php-pdo php-common && yum install -y python-setuptools && easy_install pip && pip install supervisor

# Adding the configuration file of the nginx
ADD .installer/.docker/nginx.conf /etc/nginx/nginx.conf
ADD .installer/.docker/default.conf /etc/nginx/conf.d/default.conf

# Adding the configuration file of the Supervisor
ADD .installer/.docker/supervisord.conf /etc/

#Add project
#ADD . /var/
#ADD . /opt/


ADD .installer/.docker/composer.phar /usr/local/sbin/composer
RUN chmod +x /usr/local/sbin/composer && cd /opt/ && php /usr/local/sbin/composer install && /usr/local/sbin/composer install-bootstrap

# Set the port to 80
EXPOSE 80

# Executing supervisord
CMD ["supervisord", "-n"]
