FROM python:3.7

ENV PYTHONUNBUFFERED 1

COPY ./requirements.txt /app/requirements.txt

WORKDIR /app
EXPOSE 5000
RUN pip install -r requirements.txt

COPY . /app

ENTRYPOINT flask run --host=0.0.0.0
