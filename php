#!/usr/bin/env bash
#echo  /www/$1
a=$*
docker run --rm -it -v $PWD:/www  ccq18/php-cli:7.1-v2  php /www/$1 ${a##*$1}

#docker run --rm -it -v $PWD:/www  ccq18/php-cli:7.1-v1  /www/artisan $*