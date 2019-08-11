## 结构和功能
用户
Illuminate\Contracts\Auth\Authenticatable

### 提供器
实现 \Illuminate\Contracts\Auth\UserProvider

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