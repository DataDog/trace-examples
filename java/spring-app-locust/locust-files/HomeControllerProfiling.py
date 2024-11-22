from locust import HttpLocust, TaskSet, task

class HomePageBehavior(TaskSet):

    @task(1)
    def index(self):
        self.client.get("/")

class WebsiteHomePage(HttpLocust):
    task_set = HomePageBehavior
    min_wait = 100
    max_wait = 100
    host = 'http://localhost:8080'
