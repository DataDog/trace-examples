FROM python:3.7.7

RUN apt-get update


RUN pip3 install pynamodb

RUN pip3 install ddtrace

ADD pynamodb-test /home/pynamodb-test

WORKDIR /home/pynamodb-test
