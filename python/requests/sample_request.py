from ddtrace import patch
patch(requests=True)

import requests

from ddtrace import config

config.http.trace_headers('*', integrations='requests')

headers = {'user-agent': 'my-app/0.0.1'}
r = requests.get('https://api.github.com/events', headers=headers)
