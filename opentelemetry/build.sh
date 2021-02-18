#!/bin/sh

minikube start
eval $(minikube docker-env)

docker build -t python-microservice ./python-microservice --no-cache
docker build -t node-microservice ./node-microservice --no-cache
docker build -t ruby-microservice ./ruby-microservice --no-cache
