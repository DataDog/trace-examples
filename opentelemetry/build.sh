minikube start
eval $(minikube docker-env)

docker build -t node-microservice ./node-microservice
docker build -t python-microservice ./python-microservice
