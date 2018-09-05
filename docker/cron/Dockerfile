FROM ccq18/php-cli:7.1-v1
COPY ./cron /etc/cron.d/
RUN cat /etc/cron.d/cron >>/var/spool/cron/crontabs/root
CMD touch /var/log/cron.log  && crond && tail -f /var/log/cron.log