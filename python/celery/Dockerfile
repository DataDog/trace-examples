FROM python:3.6-alpine

WORKDIR /app
ADD requirements.txt /app/
RUN pip install -r requirements.txt
ADD . /app/

CMD ["ddtrace-run", "python", "app.py"]
