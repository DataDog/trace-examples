# Getting Started #

These steps will get this sample Django application running for you using DigitalOcean.
This application is the standard [Django Polls Tutorial](https://docs.djangoproject.com/en/3.1/intro/tutorial01/) with an added page at `/`.
This app uses a Postgres database by default. If you want to change this to use 
a local sqlite db (this will no persist deployments) then you'll need to set
an environment variable specified below.

**Note: Following these steps will result in charges for the use of DigitalOcean services**

## Requirements

* You need a DigitalOcean account. If you don't already have one, you can sign up at https://cloud.digitalocean.com/registrations/new
    
## Forking the Sample App Source Code

To use all the features of App Platform, you need to be running against your own copy of this application. To make a copy, click the Fork button above and follow the on-screen instructions. In this case, you'll be forking this repo as a starting point for your own app (see [Github documentation](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo) to learn more about forking repos.

After forking the repo, you should now be viewing this README in your own github org (e.g. `https://github.com/<your-org>/sample-django`)

## Deploying the App ##

1. Visit https://cloud.digitalocean.com/apps (if you're not logged in, you may see an error message. Visit https://cloud.digitalocean.com/login directly and authenticate, then try again)
1. Click "Launch App" or "Create App"
1. Choose GitHub and authenticate with your GitHub credentials.
1. Under Repository, choose this repository (e.g. `<your-org>/sample-django`) and click **Next**.
1. On the next screen you will be prompted for the name of your app, which region you wish to deploy to, which branch you want deployments to spin off of and whether or not you wish to autodeploy the app every time an update is made to this branch. Fill this out according to how you want your app to function and click **Next**.
1. Once you have reached the next screen you will be prompted about environment variables, build and run commands, and if you want to deploy a database. 
    1. Click **Edit** next to the **Environment Variables** section and add the following :
        1. If you wish to launch the Django app in Debug mode, set `DEBUG` to `True`
        1. Set `DJANGO_ALLOWED_HOSTS` to `${APP_DOMAIN}` to set the `allowed_hosts` setting of your app to the default domain provided by DigitalOcean.
        1. Set `DATABASE_URL` to `${<YOUR_DB_NAME>.DATABASE_URL}`. You will set the database name in a next step. For simplicity, just name it `db` so the example would look like `${db.DATABASE_URL}`
        1. You can find more information about all the different environment variables [here](#environment-variables).
1. Modify the **Run Command** setting to point to your application. For this example my project is named `mysite`. So the modified command would be `gunicorn --worker-tmp-dir /dev/shm mysite.wsgi`. 
1. There is no need to modify the **Build Command** section
1. Click **Add a Database** to add the necessary Postgres db. For simplicity sake, just name it `db`. You can also connect an existing DBaaS instance if you choose. 
1. Click "Next".
1. Confirm your Plan settings and how many containers you want to launch and click **Launch Basic/Pro App**.
1. You should see a "Building..." progress indicator. And you can click "Deployments"→"Details" to see more details of the build.
1. It can currently take 5-6 minutes to build this app, so please be patient. Live build logs are coming soon to provide much more feedback during deployments.
1. Once the build completes successfully, click the "Live App" link in the header and you should see your running application in a new tab, displaying the home page.
1. You will still need to perform the following tasks from the dashboard console to finish setting up your Django application:
    1. Navigate to the console tab
    1. Run `python manage.py migrate` to perform the initial database migration. You will only need to run this the very first time you deploy your app
    1. Run `python manage.py createsuperuser` and follow the prompts to create a super user to access at `/admin`.

## Environment Variables ##
Many of the Django settings necessary to have an app run on App Platform have been exposed as environment variables. Below 
is a list and what they do

* *DEBUG* - Set Django to debug mode. Defaults to `False`.
* *DJANGO_ALLOWED_HOSTS* - The hostnames django is allowed to receive requests from. This defaults to `127.0.0.1,localhost`. Can be a comma deliminated list. For more information about `allowed_hosts` view the [django documentation](https://docs.djangoproject.com/en/3.1/ref/settings/#allowed-hosts).
* *DEVELOPMENT_MODE* - This determines whether to use a Postgres db or local sqlite. Defaults to `False` therefore using the Postgres db
* *DATABASE_URL* - The connection url including port, username, and password to connect to a postgres db. This is provided by App Platform. Required if *DEVELOPMENT_MODE* is `False`.

## Making Changes to Your App ##

As long as you left the default Autodeploy option enabled when you first launched this app, you can now make code changes and see them automatically reflected in your live application. During these automatic deployments, your application will never pause or stop serving request because the App Platform offers zero-downtime deployments.

Here's an example code change you can make for this app:
1. Edit code within the repository
1. Commit the change to master. Normally it's a better practice to create a new branch for your change and then merge that branch to master after review, but for this demo you can commit to master directly.
1. Visit https://cloud.digitalocean.com/apps and navigate to your sample-python app.
1. You should see a "Building..." progress indicator, just like above.
1. Once the build completes successfully, click the "Live App" link in the header and you should see your updated application running. You may need to force refresh the page in your browser (e.g. using Shift+Reload).

## Learn More ##

You can learn more about the App Platform and how to manage and update your application at https://www.digitalocean.com/docs/apps/.


## Deleting the App #

When you no longer need this sample application running live, you can delete it by following these steps:
1. Visit the Apps control panel at https://cloud.digitalocean.com/apps
1. Navigate to the sample-python app
1. Choose "Settings"->"Destroy"

This will delete the app and destroy any underlying DigitalOcean resources

**Note: If you don't delete your app, charges for the use of DigitalOcean services will continue to accrue.**
