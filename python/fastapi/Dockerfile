FROM python:3.8.6
USER root
WORKDIR /app
ADD . /app
RUN pip install --trusted-host pypi.python.org -r requirements.txt
CMD ["ddtrace-run", "uvicorn", "app:app"]
