使用：
FROM ccq18/php-supervisor
COPY supervisord.conf /etc/supervisord.conf

supervisord.conf必须加上以下内容让 supervisord在前台执行
[supervisord]
nodaemon=true