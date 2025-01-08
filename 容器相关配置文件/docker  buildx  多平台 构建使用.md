# docker  buildx  多平台 构建使用
     默认 你从mac 创建的镜像 只支持mac的电脑使用     
     无法多平台使用 例如 linux 相关的系统
     
     需要把 experimental  docker.config的配置 变为true
     
     
     查看相关平台的架构 命令
     uname -a  
     
     常用的架构
     linux/amd64, linux/arm/v6, linux/arm/v7, linux/arm64/v8, linux/386, linux/ppc64le, linux/s390x
     
     可以用docker的的buildx  构建多平台镜像或者包 
     相关命令
      查看版本
      docker buildx version
      查看实例
      docker buildx ls
      创建一个构建器
      docker buildx create --name mybuilder
      使用构建器
      docker buildx use mybuilder
      
      
      使用命令
    sudo docker buildx build   --platform linux/amd64 -f ./Dockerfile -t fengdejiyi/php74:v1 --  output=type=tar,dest=linux_new.tar .   
      sudo: 以超级用户权限运行命令。Docker 通常需要 root 权限，尤其在 Linux 上。  
      docker buildx build: 使用 buildx 命令构建镜像。buildx 是 Docker 的扩展工具，支持跨平台构建、多架构镜像生成等功能。   
      --platform linux/amd64: 指定镜像要构建的目标平台。在这里选择了 linux/amd64，也就是 Linux 系统的 x86-64 架构。   
      -f ./Dockerfile: 指定 Dockerfile 文件路径，这里指定了 ./  Dockerfile，即当前目录下的 Dockerfile。  
      -t fengdejiyi/php74:v1: 给镜像设置一个标签（tag），以便标识和管理。这里的标签是 fengdejiyi/php74:v1。  
      --output=type=tar,dest=linux_new.tar: 指定输出类型和目标位置。type=tar 将镜像导出为 .tar 文件，而 dest=linux_new.tar 指定导出文件名为 linux_new.tar。  
       .: 表示 Docker 构建的上下文路径为当前目录。  
	
    	生成的tar 即为镜像
    	使用常规导入
    	docker load < linux_new.tar  
    	如果上面的不行的话使用
    	cat linux.tar  |docker import - fengdejiyi/php74:hks
    	
    	成功之后  docker images 查看
    	
  ----------------------------------------
    推动到远程 
    docker buildx build --platform linux/amd64,linux/arm64,linux/arm/v7 -t fengdejiyi/php74:v1 --push .    
    docker buildx build：使用 Docker Buildx 工具来构建镜像。Buildx 是 Docker 提供的一个多平台构建工具，可以生成支持不同 CPU 架构的镜像。
   	--platform linux/amd64,linux/arm64,linux/arm/v7：指定构建的目标平台。这里指定了三种平台：
    linux/amd64：适用于大多数 x86_64 架构的服务器和电脑。
    linux/arm64：适用于 ARM64 架构，如苹果 M1/M2 芯片和部分服务器。
    linux/arm/v7：适用于 ARMv7 架构的设备，如某些树莓派（Raspberry Pi）型号。
	-t fengdejiyi/php74:v1：为生成的镜像指定标签，其中：
	fengdejiyi/php74 是镜像名称。
    v1 是标签，通常用于区分版本或环境。
    --push：构建完成后将镜像推送到 Docker Hub 或其他镜像仓库。
    .：指定构建上下文为当前目录（即当前目录的 Dockerfile 会被用于构建镜像）。  	
      
  
  
  如果遇到翻墙问题 建议用  
     模式选择 规则模式 +tun模式   不需要系统代理
    
  
  

 
 
 


