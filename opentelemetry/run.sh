#!/bin/sh

usage() { echo "Usage: $0 [start/stop/update]" 1>&2; exit 1; }

case "$1" in
    start)
        kubectl apply -f k8s-collector.yml
        ;;
    stop)
        kubectl delete -f k8s-collector.yml
        ;;
    update)
        kubectl replace --force -f ./k8s-collector.yml
        ;;
    *)
        usage
        ;;
esac