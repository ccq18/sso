# 单点登录项目
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