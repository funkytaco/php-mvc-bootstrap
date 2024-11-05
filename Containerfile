FROM ubi9/php-81
LABEL maintainer="lgonzal@redhat.com"
WORKDIR /opt/app-root/
USER root

COPY ApplicationTasks.php /opt/app-root/
COPY .installer /opt/app-root/.installer
COPY vendor /opt/app-root/vendor/
COPY src /opt/app-root/src/
COPY composer.json /opt/app-root/
ADD public /opt/app-root/public/

# Add S2I scripts
LABEL io.openshift.s2i.scripts-url=image:///usr/libexec/s2i

# Install the dependencies
RUN TEMPFILE=$(mktemp) && \
    curl -o "$TEMPFILE" "https://getcomposer.org/installer" && \
    php <"$TEMPFILE" && \
    ./composer.phar install --ignore-platform-reqs --optimize-autoloader && ./composer.phar install-mvc

#RUN /usr/libexec/s2i/assemble
COPY ./s2i/bin/ /usr/libexec/s2i

#RUN /usr/libexec/s2i/assemble
EXPOSE 8082
# Run script uses standard ways to configure the PHP application
# and execs httpd -D FOREGROUND at the end
# See more in <version>/s2i/bin/run in this repository.
# Shortly what the run script does: The httpd daemon and php needs to be
# configured, so this script prepares the configuration based on the container
# parameters (e.g. available memory) and puts the configuration files into
# the approriate places.
# This can obviously be done differently, and in that case, the final CMD
# should be set to "CMD httpd -D FOREGROUND" instead.
CMD ["/usr/libexec/s2i/run"]
