#!/usr/bin/env bash

chmod -R 777 storage;
(
cd docker;
docker-compose  -p sso  stop;
docker-compose -p sso up --build -d;
docker system prune -f;
)