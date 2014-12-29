$init_update = <<SCRIPT
if [[ ! -f /apt-get-run ]]; then apt-get upgrade && touch /apt-get-run; fi
SCRIPT

$init_script = <<SCRIPT
apt-get install -y build-essential curl python-software-properties git git-core
apt-add-repository ppa:ondrej/php5 -y && apt-get update
apt-get install -y php5-cli
apt-get install -y php5-curl php5-mcrypt php5-mysql php5-intl php5-gd
sh -c "echo '; configuration for php ZendOpcache module\n; priority=05\nzend_extension=opcache.so\nopcache.enable = Off' > /etc/php5/mods-available/opcache.ini"

curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

MYSQL_PASSWORD="password"

echo "mysql-server-5.5 mysql-server/root_password password $MYSQL_PASSWORD" | debconf-set-selections
echo "mysql-server-5.5 mysql-server/root_password_again password $MYSQL_PASSWORD" | debconf-set-selections
apt-get install -y php5-mysql mysql-client mysql-server-5.5
service mysql restart
echo "CREATE DATABASE pe_parser;" | mysql -u root -p$MYSQL_PASSWORD

apt-get install -y mc

apt-get install -y ruby1.9.1
gem install pedump

SCRIPT

$project_home = "/home/vagrant/project"
$malware_dir = "/home/vagrant/malware"
$bening_dir = "/home/vagrant/bening"
$script = <<SCRIPT
composer global require "fxp/composer-asset-plugin:1.0.*@dev"
cd #{$project_home} && composer install --prefer-dist

cd #{$project_home} && nohup php -S 0.0.0.0:8080 -t web/ > /dev/null 2>&1 &
SCRIPT

customize_cpus = "1"
customize_memory = "512"

Vagrant.configure("2") do |config|
    config.vm.box = "precise64"

    config.vm.network :forwarded_port, guest: 8080, host: 8082
    config.vm.network :private_network, ip: "192.168.66.112"

    config.ssh.forward_agent = true

    config.vm.provider :virtualbox do |vb, override|
        override.vm.box = "hashicorp/precise64"

        vb.customize ["modifyvm", :id, "--cpus",   customize_cpus]
        vb.customize ["modifyvm", :id, "--memory", customize_memory]
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    end

    config.vm.provider :parallels do |p, override|
        override.vm.box = "parallels/ubuntu-12.04"

        p.customize ["set", :id, "--cpus",   customize_cpus]
        p.customize ["set", :id, "--memsize", customize_memory]
    end

    config.vm.synced_folder "./site", $project_home
    config.vm.synced_folder "./malware", $malware_dir
    config.vm.synced_folder "./bening",  $bening_dir

    config.vm.provision :shell, :privileged => true, :inline => $init_update
    config.vm.provision :shell, :privileged => true, :inline => $init_script
    config.vm.provision :shell, :privileged => false, :inline => $script
end
