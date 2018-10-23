from ddtrace import patch
patch(requests=True)

import requests

from ddtrace import config

config.requests.http.trace_headers(['user-agent', 'Transfer-Encoding'])

headers = {'user-agent': 'my-app/0.0.1'}
response = requests.get('https://api.github.com/events', headers=headers)

print ('Received response headers', getattr(response, 'headers', {}))
