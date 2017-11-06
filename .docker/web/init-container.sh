#!/usr/bin/env bash

APACHE_VHOST=/etc/apache2/sites-available/000-default.conf
APACHE_ROOT=/var/www/html
APACHE_LOG_NAME=surveythor_demo

# ###############################################
# Prepare Apache VHost
# ###############################################
rm "$APACHE_VHOST"

sh -c "echo 'LogFormat \"%h %l %u %t \\\"%r\\\" %>s %O \\\"%{Referer}i\\\" \\\"%{User-Agent}i\\\" \\\"%D\\\" \\\"%{X-Route}o\\\"\" symfony'>> $APACHE_VHOST"
sh -c "echo ''                                                                          >> $APACHE_VHOST"
sh -c "echo '<VirtualHost *:80>'                                                        >> $APACHE_VHOST"
sh -c "echo '    DocumentRoot ${APACHE_ROOT}/web'                                       >> $APACHE_VHOST"
sh -c "echo '    Header Set X-Robots-Tag none'                                          >> $APACHE_VHOST"
sh -c "echo '    <Directory ${APACHE_ROOT}/web>'                                        >> $APACHE_VHOST"
sh -c "echo '        # enable the .htaccess rewrites'                                   >> $APACHE_VHOST"
sh -c "echo '        AllowOverride All'                                                 >> $APACHE_VHOST"
sh -c "echo '        Order allow,deny'                                                  >> $APACHE_VHOST"
sh -c "echo '        Allow from All'                                                    >> $APACHE_VHOST"
sh -c "echo '    </Directory>'                                                          >> $APACHE_VHOST"
sh -c "echo '    ErrorLog /var/log/apache2/${APACHE_LOG_NAME}_error.log'                >> $APACHE_VHOST"
sh -c "echo '    CustomLog /var/log/apache2/${APACHE_LOG_NAME}_access.log symfony'      >> $APACHE_VHOST"
sh -c "echo '</VirtualHost>'                                                            >> $APACHE_VHOST"

# ###############################################
# Prepare PHP
# ###############################################
sh -c "echo '[PHP]'                                                     >> /usr/local/etc/php/php.ini"
sh -c "echo '; Maximum amount of memory a script may consume'           >> /usr/local/etc/php/php.ini"
sh -c "echo 'memory_limit = -1'                                         >> /usr/local/etc/php/php.ini"
sh -c "echo 'short_open_tag = Off'                                      >> /usr/local/etc/php/php.ini"
sh -c "echo ''                                                          >> /usr/local/etc/php/php.ini"
sh -c "echo '[Date]'                                                    >> /usr/local/etc/php/php.ini"
sh -c "echo '; Defines the default timezone used by the date functions' >> /usr/local/etc/php/php.ini"
sh -c "echo 'date.timezone=\"Europe/Berlin\"'                           >> /usr/local/etc/php/php.ini"
sh -c "echo ''                                                          >> /usr/local/etc/php/php.ini"
sh -c "echo '[Performance Symfony related]'                             >> /usr/local/etc/php/php.ini"
sh -c "echo 'opcache.max_accelerated_files = 20000'                     >> /usr/local/etc/php/php.ini"
sh -c "echo 'realpath_cache_size = 4096K'                               >> /usr/local/etc/php/php.ini"
sh -c "echo 'realpath_cache_ttl = 600'                                  >> /usr/local/etc/php/php.ini"

pecl install zip
pecl install xdebug

sh -c "echo 'extension=zip.so'  >> /usr/local/etc/php/conf.d/zip.ini"

sh -c "echo 'zend_extension='$(find /usr/local/lib/php/extensions/ -name xdebug.so)  >  /usr/local/etc/php/conf.d/xdebug.ini"
sh -c "echo 'xdebug.remote_enable=1'                                                 >> /usr/local/etc/php/conf.d/xdebug.ini"
sh -c "echo 'xdebug.remote_autostart=1'                                              >> /usr/local/etc/php/conf.d/xdebug.ini"
sh -c "echo 'xdebug.remote_host=172.17.0.1'                                          >> /usr/local/etc/php/conf.d/xdebug.ini"
