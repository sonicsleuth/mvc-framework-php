#  Bootstrap.sh
#  Created by Hobo on 6/23/17
#
#  After your virtual machine starts for the first time,
#  it will be "provisioned". The provisioning process will
#  allow you to do additional setup tasks like installing
#  software, configuring services, etc.
#
#  This file will be executed during the provisioning process.
#
#  Simply type standard shell commands below as you would
#  normally run them in a terminal window.

# ENABLE HTACCESS: 
# Optional - If you are setting up a framework that is dependent on Apache .htaccess file 
# then you must SSH into the Vagrant machine and add the fooling to the default VHOST file, 
# then restart apache - "service apache2 restart".
# ----------------------------
# <Directory "/var/www/public">
#     Require all granted
#     Allow from all
#     Order allow,deny
#     AllowOverride All
#     Options Indexes FollowSymLinks
#  </Directory>
# ----------------------------

mysqlpass=monkeybones
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password '$mysqlpass' '
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password '$mysqlpass' '

sudo apt-get update
sudo apt-get install -y apache2
sudo apt-get install -y php5
sudo apt-get install -y mysql-server php5-mysql
sudo apt-get install -y php5-gd
sudo apt-get install -y php5-imagick
sudo apt-get install curl php5-cli git
sudo service apache2 reload

# Remove the following if you DO NOT want to upgrade to PHP7
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get -y install php7.0
sudo apt-get -y install php7.0-mysql
sudo apt-get -y install php libapache2-mod-php
sudo apt-get -y install php7.0-mbstring
sudo apt-get -y install php7.0-gd
sudo apt-get -y install php-imagick
sudo apt-get -y install php7.0-curl
sudo a2dismod php5
sudo a2enmod php7.0


if [ ! -h /var/www ]; 
then 
    #sudo mkdir /vagrant/public
    sudo rm -rf /var/www/html
    sudo ln -s /vagrant/public /var/www/html
    sudo a2enmod rewrite
    #Replace "AllowOverride None" within the *.conf file after making a backup of the file. 
    sudo sed -i.bak 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf
    sudo service apache2 restart
fi
