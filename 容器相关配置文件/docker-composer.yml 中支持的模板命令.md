#docker-composer.yml 中支持的模板命令

a.build 指令通过docker-compose在启动容器之前现根据Dockerfile构建镜像,然后根据构建镜像启动容器  
b.command 指令 覆盖容器启动后默认执行的命令  
c.container name 指令 用来指定docker-compose启动容器名称 注意:不推荐指定容器名称指令  
d.depends on  ，解决容器的依赖、启动先后的问题   
   注意:当前服务不会等待 被依赖服务「完全启动」之后才启动  

f.env file 指令用来给容器启动指定环境变量 相当于docker run -e选项   
e.environment 指令用来给容器启动指定环境变量文件 相当于dockerrun -e选项  
g.expose 指令用来指定构建镜像过程中容器暴露的端口号  
h.image  用来指定启动容器使用镜像是谁 相当于 docker run指令image(镜像名)  
i.networks 指令用来指定启动容器使用网桥 相当于 docker run --network  
j.ports  指令用来指定宿主机和容器端口映射 相当于 docker run -p  
k.volumes 指令用来指定宿主机中容器目录目录映射docker run一v  
l.restart  指令 用来指定docker容器(服务)总是运行--restart=always docker run



docker-compose  和 docker compose 一个新版本一个是老版本的命令
