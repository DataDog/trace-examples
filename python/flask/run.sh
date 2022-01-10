#!/bin/sh

docker run --name mynginx1 -p 80:5000 -d nginx
docker run --name idonate1 idonate
docker run --name idonate2 idonate
docker run --name idonate3 idonate

