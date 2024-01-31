# ThinkPHP Gateway Worker 服务

一个简单的ThinkPHP GatewayWorker 扩展中的Worker服务
本服务主要负责GatewayWorker网络IO

## 安装

~~~
composer require axguowen/think-gateway-worker
~~~

## 配置

首先配置config目录下的gatewayworker.php配置文件。
配置项说明：

~~~php
return [
    // Gateway进程名称, 方便status命令中查看统计
    'name' => 'think-gateway-worker',
    // Gateway进程监听协议, 支持http://、websocket://、text://
    'protocol' => 'text://',
    // Gateway进程监听的IP, 是暴露给客户端的让其连接的
    // 1、如果写0.0.0.0代表监听本机所有网卡，也就是内网、外网、本机都可以访问到
    // 2、如果是127.0.0.1，代表只能本机通过127.0.0.1访问，外网和内网都访问不到
    // 3、如果是内网ip例如:192.168.10.11，代表只能通过192.168.10.11访问，也就是只能内网访问，本机127.0.0.1也访问不了（如果监听的ip不属于本机则会报错）
    // 4、如果是外网ip例如110.110.110.110，代表只能通过外网ip 110.110.110.110访问，内网和本机127.0.0.1都访问不了(如果监听的ip不属于本机则会报错)
    'listen' => '0.0.0.0',
    // Gateway进程监听端口, 端口不能大于65535，请确认端口没有被其它程序占用，否则启动会报错。
    // 如果端口小于1024，需要root权限运行GatewayWorker才能有权限监听，否则报错没有权限。
    'port' => 8089,
    // Gateway进程数量, Gateway进程数不是开得越多越好，Gateway进程增多会导致进程间通讯开销变大。
    // 每个Gateway进程可以轻松处理5000连接的请求转发，业务同时在线连接数少于5000时可以只开1-2个Gateway进程。
    // 1万同时在线可以开2-3个Gateway进程，每5000个连接增加一个Gateway进程，依次类推。
    'count' => 1,
    // Gateway所在服务器的内网IP，默认填写127.0.0.1即可。
    // 多服务器分布式部署的时候需要填写真实的内网ip，不能填写127.0.0.1。
    // 注意：lanIp只能填写真实ip，不能填写域名或者其它字符串，无论如何都不能写0.0.0.0。
    'lan_ip' => '127.0.0.1',
    // Gateway内部通讯起始端口，Gateway进程启动后会监听一个本机端口，用来给BusinessWorker提供链接服务，
    // 然后Gateway与BusinessWorker之间就通过这个连接通讯。
    // 注意：这里设置的是Gateway监听本机端口的起始端口。
    // 比如启动了4个Gateway进程，startPort为4000，则每个Gateway进程分别启动的本地端口一般为4000、4001、4002、4003。
    // 当本机有多个Gateway/BusinessWorker项目时，需要把每个项目的startPort设置成不同的段
    'start_port' => 4000,
    // 注册服务地址, 格式类似于 '127.0.0.1:1236'。
    // 如果是部署了多个register服务则格式是数组，类似['192.168.0.1:1236','192.168.0.2:1236']
    'register_address' => '127.0.0.1:1236',
    // Gateway通讯密钥
    'secret_key' => '',
    // 心跳检测时间间隔，单位：秒。如果设置为0代表不做任何心跳检测。
    'ping_interval' => 50,
    // 心跳检测频率，客户端连续$pingNotResponseLimit次$pingInterval时间内不发送任何数据(包括但不限于心跳数据)则断开链接，并触发onClose。
    // 如果设置为0代表客户端不用发送心跳数据，即通过TCP层面检测连接的连通性（极端情况至少10分钟才能检测到连接断开，甚至可能永远检测不到）
    'ping_not_response_limit' => 1,
    // 心跳数据，当需要服务端定时给客户端发送心跳数据时，$gateway->pingData设置为服务端要发送的心跳请求数据。
    // 心跳数据是任意的，只要客户端能识别即可，客户端收到心跳数据可以忽略不做任何处理。
    'ping_data' => 'ping',
    // Gateway进程启动后的回调函数
    'on_worker_start' => null,
    // Gateway进程关闭的回调函数
    'on_worker_stop' => null,
    // 当有客户端连接上来时触发的回调函数
    'on_connect' => null,
    // 当有客户端连接关闭时触发的回调函数
    'on_close' => null,
    // 是否以守护进程启动
    'daemonize' => false,
];
~~~

## 启动停止

定时任务的启动停止均在命令行控制台操作，所以首先需要在控制台进入tp目录

### 启动命令

~~~
php think gateway:worker start
~~~

要使用守护进程模式启动可以将配置项deamonize设置为true
或者在启动命令后面追加 -d 参数，如下：
~~~
php think gateway:worker start -d
~~~

### 停止
~~~
php think gateway:worker stop
~~~

### 查看进程状态
~~~
php think gateway:worker status
~~~

## 注意
Windows下不支持多进程设置，也不支持守护进程方式运行，正式生产环境请用Linux