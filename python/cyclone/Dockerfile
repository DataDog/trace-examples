FROM python:2.7
WORKDIR /app
ADD . /app
RUN pip install -r requirements.txt
CMD ["ddtrace-run", "python", "app.py"]
