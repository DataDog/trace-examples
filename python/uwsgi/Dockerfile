FROM python:3.7

RUN pip install ddtrace
RUN pip install uwsgi

COPY . /code
WORKDIR /code

CMD uwsgi --ini uwsgi.ini