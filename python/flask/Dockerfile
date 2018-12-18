FROM python:3.7.1

COPY . /code
WORKDIR /code

RUN pip install pipenv
RUN pipenv install --deploy

EXPOSE 5000
CMD ["pipenv", "run", "ddtrace-run", "python", "run.py"]
