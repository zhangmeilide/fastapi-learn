FROM nginx:stable

COPY nginx.conf /etc/nginx/
###尽量命令不要写在一起否则上传到 远程docker上会很慢 一个命令一层镜像
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y logrotate
RUN apt-get install openssl -y
RUN apt-get install bash  -y
RUN apt-get install curl  -y

# 检查并创建 www-data 组和用户
RUN set -x ; \
    getent group www-data || groupadd -g 82 www-data ; \
    id -u www-data &>/dev/null || useradd -u 82 -g www-data www-data

CMD ["nginx"]

EXPOSE 80 81 443
