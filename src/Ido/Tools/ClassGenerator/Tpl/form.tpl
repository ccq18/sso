<html>
<head></head>
<body>
<form action="{{$action}}" method="{{$method}}">
{% foreach($formData as $v){ %}
    {{$v['remark']}}:
{% if($v['type']=='textarea' || strlen($v['value'])>25){ %}
    <br><textarea name="{{$v['name']}}">{{$v['value']}}</textarea>
{% }else{ %}
<input type="{{$v['type']}}" name="{{$v['name']}}" value="{{$v['value']}}">
{% } %}<br>
{%}%}

    <input type="submit" value="提交">

</form>
</body>
</html>