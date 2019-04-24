FROM python:3.6

RUN mkdir /code
WORKDIR /code
COPY Pipfile* /code/

RUN pip install pipenv
RUN pipenv install --system --deploy

ADD mysite /code/mysite
WORKDIR /code/mysite
