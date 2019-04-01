from locust import HttpLocust, TaskSet, task

class HomePageBehavior(TaskSet):

    @task(1)
    def index(self):
        self.client.get("/")

class WebsiteHomePage(HttpLocust):
    task_set = HomePageBehavior
    min_wait = 100
    max_wait = 100
    host = 'http://127.0.0.1:8080'
