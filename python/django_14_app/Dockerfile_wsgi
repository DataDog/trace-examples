FROM ubuntu

RUN apt-get update
RUN apt-get install -y \
    apt-utils \
    vim \
    curl \
    apache2 \
    apache2-utils \
    git \
    python \
    python-pip \
    libapache2-mod-wsgi

RUN pip install --upgrade pip
RUN pip install \
    'django==1.4.22' \
    git+https://github.com/Datadog/dd-trace-py.git@django-legacy-support

RUN ln -sf /proc/self/fd/1 /var/log/apache2/access.log && \
    ln -sf /proc/self/fd/1 /var/log/apache2/error.log

ADD ./wsgi_site.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/app

EXPOSE 80
CMD sh -c "python manage.py syncdb --noinput; apache2ctl -D FOREGROUND"
