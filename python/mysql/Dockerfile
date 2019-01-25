FROM ubuntu:trusty

WORKDIR /opt/code

ENV PYENV_ROOT="/opt/pyenv" \
    PATH="/opt/pyenv/bin:/opt/pyenv/shims:$PATH" \
    LC_ALL="C.UTF-8" \
    LANG="C.UTF-8"

RUN apt-get update && apt-get install -y --no-install-recommends \
        build-essential \
        ca-certificates \
        curl \
        git \
        libbz2-dev \
        libffi-dev \
        libncurses5-dev \
        libncursesw5-dev \
        libreadline-dev \
        libsqlite3-dev \
        libssl-dev \
        llvm \
        make \
        netbase \
        pkg-config \
        tk-dev \
        wget \
        xz-utils \
        zlib1g-dev \
        libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -L https://github.com/pyenv/pyenv-installer/raw/master/bin/pyenv-installer | bash \
    && pyenv install 2.7.15 \
    && pyenv global 2.7.15

RUN pip install mysql-python
RUN pip install ddtrace

COPY demo_mysqldb.py .

ENV DATADOG_TRACE_DEBUG true

CMD ["ddtrace-run", "python", "demo_mysqldb.py"]
