 
## 演示
[演示1](http://test.auth.issue.pw/)  
账号：348578429@qq.com 123456

[演示2](http://test.service.issue.pw/)  
账号：348578429@qq.com 123456
[演示3](http://test.iword.issue.pw/)  
账号：348578429@qq.com 123456

 
 ## sso安装
 重命名 .env.example 为.env
 配置 .env 文件
 ##  配置jwt
 
 composer require tymon/jwt-auth 1.0.0-rc.4.1 
 
 
 ```
 'providers' => [
 
     ...
 
     Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
 ]
 ```
 
 ```
 php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

 ```
 
 ```
 php artisan jwt:secret

```

## 接入

1.auth.php

guards
```
'sso' => [
            'driver' => 'session',
            'provider' => 'sso_authorized_users',
        ],
```
providers 加入
```  
'sso_authorized_users' => [
               'driver' => 'sso_authorization',
           ],
```
AuthServiceProvider
``` 
Auth::provider('sso_authorization', function () {
  return new SsoUserProvider();
});
```
AppServiceProvider
```
$this->app->singleton('ssohelper', function ($app) {
      return new \SsoAuth\AuthHelper( env('AUTH_SERVER'),env('API_SECRET'));
});
$this->app->alias('ssohelper',\SsoAuth\AuthHelper::class);
```
           
           
2.middleware 加入
           
\SsoAuth\Middleware\SsoAuthToken::class,

'ssoauth' =>\SsoAuth\Middleware\SsoAuthenticate::class,


3.route.php
```
Route::group(['middleware' => ['ssoauth']], function () {
   
});
Route::get('/logout', function (){
    auth('sso')->logout();
    return redirect(resolve(SsoAuth\AuthHelper::class)->getLogoutUrl(build_url('/')));
});
```


4.登录注册链接生成

```

function login_url(){
    return resolve(SsoAuth\AuthHelper::class)->getLoginUrl(route('home'));
}

function logout_url(){
    return url('/sso/logout');
}
function register_url(){
    return resolve(SsoAuth\AuthHelper::class)->getRegisterUrl();
}
```
           
## 用户信息获取接口
/auth-token?ticket=xxxx

返回信息:
```
{
id:1,
name:"aaa",
...
}
```  
## 根据id获取用户
/user/{id}

返回信息:
```
{
id:1,
name:"aaa",
...
}
```

## 跳转登陆
/login?
登陆成功后会返回到fromUrl页面 并带上ticket=xxx


## 签名算法



```
  public function getSign($arr){
        unset($arr['sign']);
        ksort($arr);
        $str = http_build_query($arr);
        return md5($this->apiSecret.$str);
    }
```




#todo 
ssoauth 从service 解耦
简化使用

## 提交sso-auth

git subtree split --rejoin --prefix=src/Ccq18/SsoAuth --branch sso_auth_v1
git push sso_auth_v1 sso-auth:master 
