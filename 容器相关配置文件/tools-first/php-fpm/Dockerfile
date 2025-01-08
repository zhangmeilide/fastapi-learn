#FROM php:7.4.33-fpm
FROM laradock/php-fpm:latest-7.4
########zyc新加的####
# Start as root  尽量命令不要写在一起否则上传到 远程docker上会很慢 一个命令一层镜像
USER root
#zyc添加的cron添加安装 cron 的命令
RUN apt-get update
RUN apt-get install -y cron
RUN apt-get install -y procps
RUN apt-get install -y vim
RUN apt-get install -y curl
RUN apt-get install -y wget
RUN apt-get install -y telnet

###########################################################################
# SUPERVISOR:  ##zyc 注意python 必须是INSTALL_PYTHON e配置文件中必须是true
###########################################################################
RUN apt-get install -y python3
RUN apt-get install -y python3-dev
RUN apt-get install -y python3-pip
RUN apt-get install -y build-essential
RUN  pip3 install --upgrade pip
RUN  pip3 install --upgrade virtualenv
RUN pip3 install --upgrade  supervisor && \
    echo_supervisord_conf > /etc/supervisord.conf && \
    sed -i 's/\;\[include\]/\[include\]/g' /etc/supervisord.conf && \
    sed -i 's/\;files\s.*/files = supervisord.d\/*.conf/g' /etc/supervisord.conf
########zyc新加的#### \

# 安装系统依赖
RUN apt-get install -y libmagickwand-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y  libjpeg-dev
RUN apt-get install -y  libfreetype6-dev
RUN apt-get install -y  libzip-dev
RUN apt-get install -y  libssl-dev
RUN apt-get install -y imagemagick \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip
# 安装 PDO MySQL 扩展
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

# 安装 imagick 扩展
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# 安装 swoole 扩展
RUN pecl install swoole-4.8.13 \
    && docker-php-ext-enable swoole


# 清理不必要的包
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

#zyc启动cron 服务
RUN service cron start

WORKDIR /var/www

#zyc
# 最后，设置 CMD 启动 cron 服务  只能执行一个命令 多个会覆盖
#CMD ["php-fpm"]
# 确保使用 php-fpm 完整路径
#CMD ["/usr/local/sbin/php-fpm"]
CMD     supervisord -c /etc/supervisord.conf && php-fpm

EXPOSE 9000