FROM drupal:8.2-apache
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN php composer.phar require drupal/console
RUN cp sites/default/default.settings.php sites/default/settings.php
RUN ./vendor/bin/drupal site:install --force --db-type=sqlite --db-file=test.sqlite --account-pass=admin
COPY drupal_alice_fixtures.info.yml /var/www/html/modules/drupal_alice_fixtures/drupal_alice_fixtures.info.yml
RUN ./vendor/bin/drupal module:install drupal_alice_fixtures
RUN chmod a+wrx /var/www/html/test.sqlite

# We mount the module inside the Drupal container, so it doesn't have
# the required dependencies...
RUN php composer.phar require nelmio/alice "^3.0"

WORKDIR /var/www/html

