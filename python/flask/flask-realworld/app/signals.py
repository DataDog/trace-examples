from flask import (
    appcontext_popped,
    appcontext_pushed,
    appcontext_tearing_down,
    before_render_template,
    got_request_exception,
    request_finished,
    request_started,
    request_tearing_down,
    template_rendered,
)


def handle_appcontext_popped(*args, **kwargs):
    print('Signal: appcontext_popped')


def handle_appcontext_pushed(*args, **kwargs):
    print('Signal: appcontext_pushed')


def handle_appcontext_tearing_down(*args, **kwargs):
    print('Signal: appcontext_tearing_down')


def handle_before_render_template(*args, **kwargs):
    print('Signal: before_template_render')


def handle_got_request_exception(*args, **kwargs):
    print('Signal: got_request_exception')


def handle_request_finished(*args, **kwargs):
    print('Signal: request_finished')


def handle_request_started(*args, **kwargs):
    print('Signal: request_started')


def handle_request_tearing_down(*args, **kwargs):
    print('Signal: request_tearing_down')


def handle_template_rendered(*args, **kwargs):
    print('Signal: template_rendered')


def connect_signals(app):
    appcontext_popped.connect(handle_appcontext_popped)
    appcontext_pushed.connect(handle_appcontext_pushed)
    appcontext_tearing_down.connect(handle_appcontext_tearing_down)

    before_render_template.connect(handle_before_render_template, app)
    got_request_exception.connect(handle_got_request_exception, app)

    request_finished.connect(handle_request_finished, app)
    request_started.connect(handle_request_started, sender=app, weak=True)
    request_tearing_down.connect(handle_request_tearing_down, app, False)

    template_rendered.connect(handle_template_rendered, sender=app)
