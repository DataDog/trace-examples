FROM python:3.7

ENV VIRTUAL_ENV=/opt/venv
RUN python -m venv $VIRTUAL_ENV
ENV PATH="$VIRTUAL_ENV/bin:$PATH"

RUN mkdir /code
WORKDIR /code

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

ADD mysite /code/mysite
WORKDIR /code/mysite

EXPOSE 8888
