FROM opentracing/nginx-opentracing
RUN apt update && apt -y install curl jq
COPY install-dd-opentracing-cpp /
RUN /install-dd-opentracing-cpp
