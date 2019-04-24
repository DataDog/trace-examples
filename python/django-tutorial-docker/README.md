# Tracing the Django Tutorial 

Example web application based on:

- https://docs.djangoproject.com/en/2.2/intro/tutorial01/
- https://docs.djangoproject.com/en/2.2/intro/tutorial02/
- https://docs.djangoproject.com/en/2.2/intro/tutorial03/
- https://docs.djangoproject.com/en/2.2/intro/tutorial04/
- https://docs.djangoproject.com/en/2.2/intro/tutorial07/

## Setup

Start the Docker containers and web server:

``` sh
DD_API_KEY=... docker-compose up --build -d
```

Run migration scripts to set up the database:

``` sh
docker-compose exec app python manage.py makemigrations
docker-compose exec app python manage.py migrate
```

Create an admin user:

```
docker-compose exec app python manage.py createsuperuser
# Enter username, email, password
```
