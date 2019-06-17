FROM python:3.7-slim
RUN apt-get update
RUN apt-get install -y --no-install-recommends build-essential gcc

RUN python -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

COPY requirements.txt .
RUN pip install -r requirements.txt
RUN pip install pdbpp

COPY server.py .
COPY statics .

ENV DATADOG_TRACE_DEBUG=true

CMD ["ddtrace-run", "python", "server.py"]
