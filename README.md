## 赛奥科数据中心服务
本项目使用[Laravel5.5](https://laravel.com/docs/5.5) 开发.项目依赖于[l5-repository](https://github.com/andersao/l5-repository)和[Dingo api](https://github.com/dingo/api).

## Environment
- Nginx 1.8+
- PHP 7.1+
- MySQL 5.7+
- Redis 3.0+

## Install
```
$ composer install
```
#### 1. 生成配置文件和key
```
$ php artisan key:generate
```
#### 2. 修改相关配置
```
APP_ENV=local                    //这里视情况而定，本地是local，线上是production
APP_DEBUG=false                  //线上务必要关掉debug  
APP_URL=                         //这里填写ip或地址，给单元测试用
DB_HOST=127.0.0.1
DB_PORT=3306                     //数据库端口
DB_DATABASE=                     //数据库名称
DB_USERNAME=                     //数据库用户名
DB_PASSWORD=                     //数据库密码
DB_TENANT_PREFIX=                //租户数据库前缀,可通过配置关闭

BROADCAST_DRIVER=log
CACHE_DRIVER=redis               //建议缓存用reids,如无可用file
SESSION_DRIVER=redis
QUEUE_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DATABASE=3                  //redis数据库编号

API_PREFIX=data/business         //前缀
API_SUBTYPE=                     //类型
API_STANDARDS_TREE=vnd           //标准树
API_VERSION=v1                   //api 版本
API_NAME=                        //api名称，可不填
API_DEFAULT_FORMAT=json          //默认响应json
API_DEBUG=false                  //api调试,线上务必要关掉
API_STRICT=false                 //严格模式，请求需要带header头  

```
#### 3. 设置目录相关权限
```
$ mkdir -p storage/app
$ mkdir -p storage/framework/views
$ mkdir -p storage/framework/cache
$ mkdir -p storage/framework/sessions
$ chmod -R 777 storage
```

#### 4. 新建缓存文件夹
```
mkdir -R bootstrap/cache
chmod -R 777 bootstrap/cache
chmod -R 777 vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer
```

#### 5.(可选) 优化配置，提升性能
```
$ php artisan config:cache
$ php artisan optimize --force
$ composer dumpautoload
```

#### 6. 加载sak/service-base
```
php artisan vendor:publish --provider="Sak\Core\SakBaseServiceProvider"
```


#### 7.注意项目
- 可以在env配置中更改缓存驱动为redis,
- nginx需要指向到public目录下,设置下location
- 生成配置文件

#### 8.脚手架生成业务模块
```
sak:generate {class} {--module=} {--route}  Generate all api directory.
sak:rollback {name} {--module=}   Rollback all api directory.
```
- 脚手架新增模块(包括Route,Controller,Repository,Service,Model,Request,Transformer,Criteria)
```
php artisan sak:generate product --module=Products --route
php artisan sak:generate product --route
```

－ 脚手架回滚模块（路由不回滚，需要手动删除）
```
php artisan sak:rollback product --module=Products
```