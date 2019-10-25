from django.apps import AppConfig

class DemoAppConfig(AppConfig):
    name = 'demoapp'
    verbose_name = 'My Application'
    def ready(self):
        pass
