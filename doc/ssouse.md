## 1.注册中间件
SsoAuthenticate.php 登录认证的页面使用
SsoAuthToken.php 自动认证token
## 2.注册 auth
AuthServiceProvider
Auth::provider('sso_authorization', function () {
            return new SsoUserProvider();
        });
        
        
## 3.auth.php
```
 'defaults' => [
        'guard' => 'sso',
        'passwords' => 'users',
    ],

    'guards' => [
        'sso' => [
            'driver' => 'session',
            'provider' => 'sso_authorized_users',
        ],
    ],

    'providers' => [

        'sso_authorized_users' => [
            'driver' => 'sso_authorization',
        ],

    ],
    
```

todo 函数依赖