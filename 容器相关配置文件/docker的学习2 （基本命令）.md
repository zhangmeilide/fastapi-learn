1  docker images      //查看所有镜像  
2  docker ps  -a      //查看所有的容器  
3  docker search    $镜像    //查找镜像  
4  docker  pull   $镜像       //下载镜像        docker rmi    $镜像   //删除镜像   
5    创建容器 （根据镜像创建容器） 
（-d 后台运行  -t 终端  -i 交互   -p 端口（8888 对于本地端口 80 容器端口号）  --name  容器名称  --restart=always   一直执行 不停止   /zyc/selfstart/start.sh  开机启动的脚本 -v  目录映射  ）  
   docker run  -d -i -t  -p 8888:80   -v /docker/nginx/vhosts:/www/nginx/conf/vhosts -v /docker/web:/www/web  -v /docker/hosts:/etc/hosts   --name web  --restart=always  centos:v1   /zyc/selfstart/start.sh  
6  docker ps -l    // 查看正在运行的容器  
    docker ps  -a  // 查看所有的容器  
7  docker rm   （容器名或者ID）  //删除容器  
8  docker  start   （容器名或者ID）  // 开启容器  
9   docker stop    （容器名或者ID） //关闭容器  
10  docker attach    （容器名或者ID） //进入容器  
11   docker inspect   （容器名或者ID）    //查看容器详细信息  
12  进入容器后，如果想退出容器 直接输入 exit  
————————————————————————————————————  
镜像和容器的关系  
容器是根据镜像创建的 ，镜像是只可读的，容器是可以写的！  
容器可以变成镜像  
————————————————————————————————————  
13   提交容器变成镜像  
     docker commit -m 'A new image' -a 'zyc' 614122c0aabb aoct:apache2         
     // -a   作者   -m   '描述'   614122c0aabb 镜像ID     aoct:apache2 新生成的镜像  
14  导出容器  
    docker export 7691a814370e > centos6.9.tar     //导出 7691a814370e 容器ID   centos6.9.tar 导出的tar包     
15  把导出的tar 包 变为镜像     
 cat centos6.9.tar |  docker import - centos:test       //导入为镜像了  （文件导入成镜像）  
16  镜像的导入导出  
      docker save ubuntu:latest > ubuntu_save.tar    //保存镜像到一个tar包  
       docker load < ubuntu_save.tar      //加载一个tar包格式的镜像(之后的镜像信息都在，包括名字)  
       
 
 16 当写Dockerfile的时候 尽量命令不要写在一起否则上传到 远程docker上会很慢 一个命令一层镜像  
 17 docker push  多送到远程docker 服务器的时候 因为网络会很慢，需要不间断切换网络提交数据       