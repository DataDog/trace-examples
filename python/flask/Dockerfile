FROM python:3.6.8

COPY . /code
WORKDIR /code

RUN pip install pipenv
RUN pipenv install
RUN pipenv update --outdated

EXPOSE 5000
CMD ["pipenv", "run", "ddtrace-run", "python", "run.py"]
