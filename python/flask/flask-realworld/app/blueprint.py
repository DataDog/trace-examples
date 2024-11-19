from ddtrace import Pin
from flask import abort, Blueprint, render_template_string

from .limiter import limiter


# Create a new Blueprint
bp = Blueprint('bp', __name__, url_prefix='/bp/')

# Just showing that we can override the service set for this blueprint
Pin.override(bp, service='flask-bp', app='flask', app_type='web')


# Hook to run before each blueprint request
@bp.before_request
def bp_before_request():
    print('Hook: bp_before_request')


# Hook to run before each app request
@bp.before_app_request
def bp_before_app_request():
    print('Hook: bp_before_app_request')


# Hook to run before the first app request
@bp.before_app_first_request
def bp_before_app_first_request():
    print('Hook: bp_before_app_first_request')


# Hook to run after each blueprint request
@bp.after_request
def bp_after_request(response):
    print('Hook: bp_after_request')
    return response


# Hook to run after each app request
@bp.after_app_request
def bp_after_app_request(response):
    print('Hook: bp_after_app_request')
    return response


# Hook to run after the teardown of each blueprint request
@bp.teardown_request
def bp_teardown_request(response):
    print('Hook: bp_teardown_request')
    return response


# Hook to run after the teardown of each app request
@bp.teardown_app_request
def bp_teardown_app_request(response):
    print('Hook: bp_teardown_app_request')
    return response


# Endpoint which uses a rate limiter decorator
@bp.route('/')
@limiter.limit('10 per second')
def index():
    return render_template_string('<h1>Blueprint</h1>')


# Endpoint which raises a 404 error
@bp.route('/unknown')
@limiter.exempt
def unknown():
    abort(404)


# Custom 404 handler for this blueprint only
@bp.errorhandler(404)
def bp_not_found(e):
    return 'oh no....', 404
