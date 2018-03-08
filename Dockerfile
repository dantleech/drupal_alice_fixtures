FROM drupal:8.2-apache
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN php composer.phar require drupal/console
RUN cp sites/default/default.settings.php sites/default/settings.php
RUN ./vendor/bin/drupal site:install --force --db-type=sqlite --db-file=test.sqlite --account-pass=admin
COPY dtl_alice_fixtures.info.yml /var/www/html/modules/dtl_alice_fixtures/dtl_alice_fixtures.info.yml
RUN ./vendor/bin/drupal module:install dtl_alice_fixtures
RUN chmod a+wrx /var/www/html/test.sqlite

WORKDIR /var/www/html

