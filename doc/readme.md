# 单点登录项目
## 跳转登陆
/login?sign=xxxxx&return_url=xxxx
登陆成功后会返回到return_url页面 并带上token=xxx


## 用户信息获取接口
/auth-token?token=xxxx

返回信息:默认信息
```
{
id:1,
name:"aaa",
    //其他自定义信息
}
```  

## jsonp登录
/login/jsonp?sigin=xxxxx&callback=xxxx&username=xxx&password=xxx
登陆

ps：返回内容应该根据业务需要做自定义扩展
## 签名


# laravel auth原理 
## \Auth

## 获取当前已认证的用户...
```
$user = Auth::user();
// 获取当前已认证的用户 ID...
$id = Auth::id();
```
：




## 自定义auth 需要做的事情
### 模型 用于存储认证和获取数据 
实现 Illuminate\Contracts\Auth\Authenticatable
getAuthIdentifierName 得到唯一标识符名字
getAuthIdentifier 得到唯一标识符
getAuthPassword 得到密码
getRememberToken 取得token
setRememberToken  设置token
getRememberTokenName 


### 提供器
实现 \Illuminate\Contracts\Auth\UserProvider
retrieveById
根据唯一标识符取得Authenticatable
retrieveByToken 
根据唯一标识符合 token 获取 Authenticatable
updateRememberToken
更新 updateRememberToken
retrieveByCredentials
根据$credentials 获取 Authenticatable
validateCredentials
校验 $credentials 的password 和 Authenticatable的密码是否一致

### 看守器
需要实现 Illuminate\Contracts\Auth\SupportsBasicAuth;
Illuminate\Contracts\Auth\StatefulGuard

once
login 登录并且「记住」给定用户
loginUsingId  loginUsingId 方法通过其 ID 将用户记录到应用中
onceUsingId  once 方法将用户记录到单个请求的应用中
logout 退出

basic
onceBasic

## check 方法来检查用户是否登录，如果已经认证，将会返回 true
## attempt 方法会接受一个键值对数组作为其第一个参数。这个数组的值将用来在数据库表中查找用户。所以在上面的例子中，用户将被 email 字段的值检索。如果用户被找到了，数据库中存储的散列密码将与通过数组传递给方法的散列 password 进行比较。 如果两个散列密码匹配，就会为用户启动一个已认证的会话。
## viaRemember 方法来检查这个用户是否使用「记住我」 cookie 进行认证


## 配置 auth.php

defaults
设置默认的guard
guards
设置看守器和提供器
providers
提供器列表 由看守器设置
passwords
密码提供器列表，和providers的名称保持一致

### 创建provider EloquentUserProvider
### auth注册
  \Auth::provider('myeloquent', function ($app, $config) {
            return new EloquentUserProvider($this->app['hash'], $config['model']);
        });
        



## auth的方法
用户认证 
### 判断当前用户是否已认证（是否已登录）
 Auth::check();
### 获取当前的认证用户
 Auth::user();
### 获取当前的认证用户的 ID（未登录情况下会报错）
 Auth::id();
### 通过给定的信息来尝试对用户进行认证（成功后会自动启动会话）
 Auth::attempt(['email' => $email, 'password' => $password]);
### 通过 Auth::attempt() 传入 true 值来开启 '记住我' 功能
 Auth::attempt($credentials, true);
### 只针对一次的请求来认证用户
 Auth::once($credentials);
### 登录一个指定用户到应用上
 Auth::login(User::find(1));
### 登录指定用户 ID 的用户到应用上
 Auth::loginUsingId(1);
### 使用户退出登录（清除会话）
 Auth::logout();# 单点登录项目
                ## 跳转登陆
                /login?sigin=xxxxx&return_url=xxxx
                登陆成功后会返回到return_url页面 并带上token=xxx
                
                ## jsonp登录
                /login/jsonp?sigin=xxxxx&callback=xxxx&username=xxx&password=xxx
                登陆
                
                ## 用户信息获取接口
                /userinfo?token=xxxx
                
                返回信息:默认信息
                ```
                {
                "user":{
                    },
                "roles":[
                    "role1",
                    "role2",
                        ],
                    //其他自定义信息
                }
                ```  
                ps：返回内容应该根据业务需要做自定义扩展
                ## 签名
                
                
                # laravel auth原理 
                ## \Auth
                
                ## 获取当前已认证的用户...
                ```
                $user = Auth::user();
                // 获取当前已认证的用户 ID...
                $id = Auth::id();
                ```
                ：
                
                
                
                
                ## 自定义auth 需要做的事情
                ### 模型 用于存储认证和获取数据 
                实现 Illuminate\Contracts\Auth\Authenticatable
                getAuthIdentifierName 得到唯一标识符名字
                getAuthIdentifier 得到唯一标识符
                getAuthPassword 得到密码
                getRememberToken 取得token
                setRememberToken  设置token
                getRememberTokenName 
                
                
                ### 提供器
                实现 \Illuminate\Contracts\Auth\UserProvider
                retrieveById
                根据唯一标识符取得Authenticatable
                retrieveByToken 
                根据唯一标识符合 token 获取 Authenticatable
                updateRememberToken
                更新 updateRememberToken
                retrieveByCredentials
                根据$credentials 获取 Authenticatable
                validateCredentials
                校验 $credentials 的password 和 Authenticatable的密码是否一致
                
                ### 看守器
                需要实现 Illuminate\Contracts\Auth\SupportsBasicAuth;
                Illuminate\Contracts\Auth\StatefulGuard
                
                once
                login 登录并且「记住」给定用户
                loginUsingId  loginUsingId 方法通过其 ID 将用户记录到应用中
                onceUsingId  once 方法将用户记录到单个请求的应用中
                logout 退出
                
                basic
                onceBasic
                
                ## check 方法来检查用户是否登录，如果已经认证，将会返回 true
                ## attempt 方法会接受一个键值对数组作为其第一个参数。这个数组的值将用来在数据库表中查找用户。所以在上面的例子中，用户将被 email 字段的值检索。如果用户被找到了，数据库中存储的散列密码将与通过数组传递给方法的散列 password 进行比较。 如果两个散列密码匹配，就会为用户启动一个已认证的会话。
                ## viaRemember 方法来检查这个用户是否使用「记住我」 cookie 进行认证
                
                
                ## 配置 auth.php
                
                defaults
                设置默认的guard
                guards
                设置看守器和提供器
                providers
                提供器列表 由看守器设置
                passwords
                密码提供器列表，和providers的名称保持一致
                
                ### 创建provider EloquentUserProvider
                ### auth注册
                  \Auth::provider('myeloquent', function ($app, $config) {
                            return new EloquentUserProvider($this->app['hash'], $config['model']);
                        });
                        
                
                
                
                ## auth的方法
                用户认证 
                ### 判断当前用户是否已认证（是否已登录）
                 Auth::check();
                ### 获取当前的认证用户
                 Auth::user();
                ### 获取当前的认证用户的 ID（未登录情况下会报错）
                 Auth::id();
                ### 通过给定的信息来尝试对用户进行认证（成功后会自动启动会话）
                 Auth::attempt(['email' => $email, 'password' => $password]);
                ### 通过 Auth::attempt() 传入 true 值来开启 '记住我' 功能
                 Auth::attempt($credentials, true);
                ### 只针对一次的请求来认证用户
                 Auth::once($credentials);
                ### 登录一个指定用户到应用上
                 Auth::login(User::find(1));
                ### 登录指定用户 ID 的用户到应用上
                 Auth::loginUsingId(1);
                ### 使用户退出登录（清除会话）
                 Auth::logout();
                ### 验证用户凭证
                 Auth::validate($credentials);
                ### Attempt to authenticate using HTTP Basic Auth
                 ### 使用 HTTP 的基本认证方式来认证
                 Auth::basic('username');
                Perform a stateless HTTP Basic login attempt
                 ### 执行「HTTP Basic」登录尝试
                 Auth::onceBasic();
                ### 发送密码重置提示给用户
                 Password::remind($credentials, function($message, $user){});
                              
                ## 用户授权 
                ### 定义权限
                Gate::define('update-post', 'Class@method');
                Gate::define('update-post', function ($user, $post) {...});
                ### 传递多个参数
                Gate::define('delete-comment', function ($user, $post, $comment) {});
                
                ### 检查权限
                Gate::denies('update-post', $post);
                Gate::allows('update-post', $post);
                Gate::check('update-post', $post);
                ### 指定用户进行检查
                 Gate::forUser($user)->allows('update-post', $post);
                在 User 模型下，使用 Authorizable trait
                 User::find(1)->can('update-post', $post);
                User::find(1)->cannot('update-post', $post);
                
                ### 拦截所有检查
                Gate::before(function ($user, $ability) {});
                Gate::after(function ($user, $ability) {});
                

### 验证用户凭证
 Auth::validate($credentials);
### Attempt to authenticate using HTTP Basic Auth
 ### 使用 HTTP 的基本认证方式来认证
 Auth::basic('username');
Perform a stateless HTTP Basic login attempt
 ### 执行「HTTP Basic」登录尝试
 Auth::onceBasic();
### 发送密码重置提示给用户
 Password::remind($credentials, function($message, $user){});
              
## 用户授权 
### 定义权限
Gate::define('update-post', 'Class@method');
Gate::define('update-post', function ($user, $post) {...});
### 传递多个参数
Gate::define('delete-comment', function ($user, $post, $comment) {});

### 检查权限
Gate::denies('update-post', $post);
Gate::allows('update-post', $post);
Gate::check('update-post', $post);
### 指定用户进行检查
 Gate::forUser($user)->allows('update-post', $post);
在 User 模型下，使用 Authorizable trait
 User::find(1)->can('update-post', $post);
User::find(1)->cannot('update-post', $post);

### 拦截所有检查
Gate::before(function ($user, $ability) {});
Gate::after(function ($user, $ability) {});

