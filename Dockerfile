#Centos 7.4.1708 official image
FROM centos:7.4.1708
ENV xdebugversion="2.6.1"
ENV nodeversion="10.15.3"

#Intalls apache, enterprise release repo, and netstat
RUN yum install -y httpd epel-release
#Installs repo with updated php versions
RUN yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm composer
#Enable repo for php7.2
RUN yum-config-manager --enable remi-php72
#Removes unused packages
RUN yum -y autoremove
#Cleans all cached files from enabled repos
RUN yum clean all
RUN yum update -y
#Installs php and php libraries version 7.2
RUN yum install php php-pdo php-mysqlnd -y
#Adds Apache config file
ADD ./docker/httpd.conf /etc/httpd/conf/httpd.conf
ADD ./docker/php.dev.ini "/etc/php.ini"
RUN PATH=$PATH:~/.composer/vendor/bin

COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install
RUN chmod 777 storage/*
WORKDIR /
#Installs Xdebug
RUN yum install wget php-devel make -y
RUN wget https://xdebug.org/files/xdebug-$xdebugversion.tgz && tar -xvzf xdebug-*.tgz
RUN cd /xdebug-* && phpize && ./configure --enable-xdebug && make && make install

RUN wget https://nodejs.org/dist/v$nodeversion/node-v$nodeversion-linux-x64.tar.xz

RUN tar xf node-v*
#Symlinks from node to bin directory
RUN ln -s /node-v*/bin/npm /usr/bin/npm
RUN ln -s /node-v*/bin/node /usr/bin/node
RUN ln -s /node-v*/bin/npx /usr/bin/npx
RUN yum –y install openssh-server openssh-clients
RUN yum install mod_ssl -y
#ADD ./docker/server.crt /var/www/server.crt
#ADD ./docker/server.key /var/www/server.key
WORKDIR /var/www/html

EXPOSE 81
ENTRYPOINT ["httpd", "-DFOREGROUND"]
