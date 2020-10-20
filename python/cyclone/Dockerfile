FROM python:2.7
WORKDIR /app
ADD . /app
RUN apt-get update && apt-get install -y python3-dev default-libmysqlclient-dev build-essential
RUN pip install -r requirements.txt
CMD ["ddtrace-run", "python", "app.py"]
