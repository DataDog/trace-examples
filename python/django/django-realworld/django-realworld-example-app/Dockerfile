FROM python:3.7

ENV VIRTUAL_ENV=/opt/venv
RUN python -m venv $VIRTUAL_ENV
ENV PATH="$VIRTUAL_ENV/bin:$PATH"

COPY . /code
WORKDIR /code

RUN pip install --no-cache-dir -r requirements.txt

CMD ddtrace-run python manage.py runserver 0.0.0.0:3000
