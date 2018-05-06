#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with
# special-character passwords
PASSWORD='XXXXX'
PROJECTFOLDER='chromebookhandout'

# Create project folder
sudo mkdir "/var/www/html/${PROJECTFOLDER}"

# Update/upgrade
sudo apt-get udpate
sudo apt-get -y upgrade

# Install Apache 2.5 and PHP 5.5
sudo apt-get install -y apache2
sudo apt-get install -y php7.0-common

# Install MySQL and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password CBhand0utp@$$ $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again CBhand0utp@$$ $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

# Install phpmyadmin and give password(s) to installer. For simplicity, using
# same password for mysql and phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm CBhand0utp@$$ $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass CBhand0utp@$$ $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass CBhand0utp@$$ $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# Setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
  DocumentRoot "/var/www/html/${PROJECTFOLDER}"
  <Directory "/var/www/html/${PROJECTFOLDER}">
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.config

# enable mod_rewrite
sudo a2enmod rewrite

# Restart apache
service apache2 restart

# Install git
sudo apt-get -y install git

# Install Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
