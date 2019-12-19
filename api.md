      
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