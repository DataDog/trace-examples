FROM ubuntu

RUN apt-get update
RUN apt-get install -y apt-utils vim curl apache2 apache2-utils
RUN apt-get -y install python3 libapache2-mod-wsgi-py3
RUN ln /usr/bin/python3 /usr/bin/python
RUN apt-get -y install python3-pip
RUN ln /usr/bin/pip3 /usr/bin/pip
RUN pip install --upgrade pip
RUN pip install django ptvsd ddtrace

RUN ln -sf /proc/self/fd/1 /var/log/apache2/access.log && \
    ln -sf /proc/self/fd/1 /var/log/apache2/error.log

ADD ./wsgi_site.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/app

EXPOSE 80
CMD sh -c "python manage.py makemigrations polls; python manage.py migrate polls; apache2ctl -D FOREGROUND"
