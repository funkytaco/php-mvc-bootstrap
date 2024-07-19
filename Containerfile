FROM privatehub/ubi8/ubi
#begin
#ADD _build .
#COPY _build/configs/automationhub-root.crt /usr/share/pki/ca-trust-source/anchors
#RUN update-ca-trust
#end

RUN dnf -y install curl nginx php php-cli php-common php-gd php-json php-pdo php-xml php-zip python3 python3-pip python3-setuptools \
&& python3 -m pip install supervisor

RUN mkdir /run/php-fpm && chown apache:apache /run/php-fpm && chmod 777 /run/php-fpm

COPY composer.json /opt/

# Adding the configuration file of the nginx
ADD .installer/.docker/nginx.conf /etc/nginx/nginx.conf
ADD .installer/.docker/supervisord.conf /etc/supervisord.conf

COPY --from=docker.io/composer/composer /usr/bin/composer /usr/local/bin/composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer && cd /opt/ && /usr/local/bin/composer install --ignore-platform-reqs && /usr/local/bin/composer install-mvc

EXPOSE 8082

CMD ["supervisord", "-c", "/etc/supervisord.conf", "-n"]
