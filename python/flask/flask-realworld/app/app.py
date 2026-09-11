# Patching
# DEV: This is here only for those who choose not to use `ddtrace-run`
from ddtrace import patch_all; patch_all(flask=True, requests=True)  # noqa

# Datadog
from ddtrace import tracer

# Flask
from flask import Flask, Response
from flask import after_this_request
from flask import abort, jsonify, render_template, url_for
from flask.views import View
from werkzeug.routing import Rule

# Extensions
from flask_caching import Cache
from flask_cors import CORS

# Utilities
import requests

# Internal
from .blueprint import bp
from .exceptions import AppException
from .limiter import limiter
from .signals import connect_signals

# Create a new app
app = Flask(__name__)

# Register our blueprint and it's endpoints on our app
app.register_blueprint(bp)

# Configure our signals
connect_signals(app)

# Configure our middleware/extensions
CORS(app)
Cache(app, config=dict(CACHE_TYPE='simple'))
limiter.init_app(app)


# Inject the `app.url_map` into our jinja2 environment for templates
@app.context_processor
def inject_url_map():
    return dict(url_map=app.url_map)


# Hook to run before the first request
@app.before_first_request
def before_first_request():
    print('Hook: before_first_request')


# Hook to run before each request
@app.before_request
def before_request():
    print('Hook: before_request')


# Hook to run after each request
@app.after_request
def after_request(response):
    print('Hook: after_request')
    return response


# Hook to run on request teardown
@app.teardown_request
def teardown_request(response):
    print('Hook: teardown_request')
    return response


# Hook to run on app context teardown
@app.teardown_appcontext
def teardown_appcontext(appcontext):
    print('Hook: teardown_appcontext')


# Index route with rate custom rate limited decorator
@app.route('/')
@limiter.limit('10 per second')
def index():
    routes = [
        dict(
            rule='GET /',
            description=['Endpoint for this page, which uses <code>render_template()</code>.'],
            links=[
                dict(label='GET /', url=url_for('index')),
            ],
        ),
        dict(
            rule='GET /joke',
            description=[
                'Endpoint which uses <code>requests</code> to fetch a joke from icanhazdadjoke.com.',
                'This endpoint also registers a <code>flask.after_this_request</code> hook.'
            ],
            links=[
                dict(label='GET /joke', url=url_for('joke')),
            ],
        ),
        dict(
            rule='GET /json',
            description=[
                'Endpoint which uses <code>jsonify</code> to return a JSON object to the user.',
            ],
            links=[
                dict(label='GET /json', url=url_for('json')),
            ],
        ),
        dict(
            rule='GET /custom-endpoint/<msg>',
            description=[
                ('Endpoint which was registered manually using <code>@app.endpoint()</code> '
                 'and <code>app.add_url_rule()</code>'),
                'This endpoint also has a /custom-endpoint/ url configured with a default <msg>',
                ('We have also attached a @tracer.wrap() to the endpoint and added a '
                 'with tracer.trace(): to the body of the view as well.'),
            ],
            links=[
                dict(label='GET /custom-endpoint', url=url_for('custom-endpoint')),
                dict(label='GET /custom-endpoint/hello', url=url_for('custom-endpoint', msg='hello')),
            ],
        ),
        dict(
            rule='GET /custom-error',
            description=[
                'Endpoint which raises a customer user-defined Exception (non HTTPException)',
            ],
            links=[
                dict(label='GET /custom-error', url=url_for('custom_error')),
            ],
        ),
        dict(
            rule='GET /stream',
            description=[
                'Endpoint which uses a generator to stream the response back to the user.',
            ],
            links=[
                dict(label='GET /stream', url=url_for('stream')),
            ],
        ),
        dict(
            rule='GET /abort/<int:code>',
            description=[
                'Endpoint which calls <code>abort(code)</code> for us',
            ],
            links=[
                dict(label='GET /abort/{}'.format(code), url=url_for('abort_endpoint', code=code))
                for code in [400, 401, 403, 404, 500]
            ],
        ),
        dict(
            rule='GET /hello/<name>',
            description=[
                'Endpoint which was generated from a <code>flask.views.View</code>',
            ],
            links=[
                dict(label='GET /hello/Flask', url=url_for('myview', name='Flask')),
            ],
        ),
        dict(
            rule='GET /bp/<name>',
            description=[
                'Blueprint endpoint that uses <code>render_template_string()</code>',
            ],
            links=[
                dict(label='GET /bp/', url=url_for('bp.index')),
            ],
        ),
        dict(
            rule='GET /bp/unknown',
            description=[
                'Blueprint endpoint that calls <code>abort(404)</code>',
            ],
            links=[
                dict(label='GET /bp/unkown', url=url_for('bp.unknown')),
            ],
        ),
        dict(
            rule='GET /static/test.txt',
            description=[
                'Endpoint to fetch a simple .txt static file.',
            ],
            links=[
                dict(label='GET /static/test.txt', url=url_for('static', filename='test.txt')),
            ],
        ),
    ]
    return render_template('index.jinja2', routes=routes)


# Endpoint which makes uses `requests`
@app.route('/joke')
def joke():
    res = requests.get('https://icanhazdadjoke.com/', headers=dict(Accept='text/plain'))
    res.raise_for_status()

    @after_this_request
    def after_joke(response):
        print('Hook: after_this_request')
        return response

    return res.content


@app.route('/json')
def json():
    return jsonify(hello='world')


# Register url for `@app.endpoint('custom.endpoint')` below
app.url_map.add(Rule('/custom-endpoint/', endpoint='custom-endpoint', defaults=dict(msg='Hello')))
app.url_map.add(Rule('/custom-endpoint/<msg>', endpoint='custom-endpoint'))

# Handler for manually added url rule `/custom-endpoint` with manual tracing enabled
@app.endpoint('custom-endpoint')
@tracer.wrap('my-custom-endpoint')
def custom_endpoint(msg):
    with tracer.trace('my-custom-endpoint.respond'):
        return msg


# Endpoint which raises a custom Exception
@app.route('/custom-error')
def custom_error():
    raise AppException('custom app exception')


# Endpoint that streams from a generator
@app.route('/stream')
def stream():
    def generate():
        for i in range(100):
            yield '{}\n'.format(i)

    return Response(generate(), mimetype='text/plain')


# Endpoint used to abort whichever code we want (typically 400 >= code <= 500)
@app.route('/abort/<int:code>')
def abort_endpoint(code):
    abort(code)


# Custom error handler for 404's
@app.errorhandler(404)
def handle_404(e):
    return '404', 404


# Custom error handler for 500's
@app.errorhandler(500)
def handle_500(e):
    return '500', 500


# Registering a flask.views.View class as an endpoint
class MyView(View):
    methods = ['GET']

    def dispatch_request(self, name):
        return 'Hello %s!' % name


app.add_url_rule('/hello/<name>', view_func=MyView.as_view('myview'))
