#!/bin/sh

export V_SERVER=$1
export V_REMOTE=$2

cd /vagrant/vagrant/

# fix network
yum -y install NetworkManager
systemctl enable NetworkManager
systemctl start NetworkManager

# apache
yum -y install httpd
echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf
rm -f /etc/httpd/conf.d/*
envsubst < apache.conf > /etc/httpd/conf.d/apache.conf
systemctl enable httpd
systemctl start httpd

# bash alias
envsubst < .bashrc >> /home/vagrant/.bashrc

# php
yum -y install php php-mysql php-intl php-gd php-devel php-pear php-mbstring
cp php.ini /etc/php.d/php.ini
chown -R vagrant:vagrant /var/lib/php/session

# xdebug
yum -y install gcc gcc-c++
pecl install Xdebug
cp xdebug.ini /etc/php.d/

# mysql
yum -y install mariadb-server mariadb
systemctl enable mariadb
systemctl start mariadb
cp mysql.cnf /etc/my.cnf.d/

systemctl restart httpd
systemctl restart mariadb