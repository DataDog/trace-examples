import logging
import uwsgi
from ddtrace import tracer#, internal

log = logging.getLogger(__name__)


@tracer.wrap(service="uwsgi-example")
def application(env, start_response):
    start_response('200 OK', [('Content-Type', 'text/html')])
    # if uwsgi.opt["enable-threads"] != True:
    #     log.debug("enable-threads=true is required for ddtrace to run")
    #     internal.writer.on_shutdown()
    return [b"Hello World"]



# LOG.debug("enable-threads={}".format(uwsgi.opt["enable-threads"]))

# Possibly something like this for the 
# try:
#     import uwsgi
#     if uwsgi.opt["enable-threads"] != True:
#         log.debug("enable-threads=true is required for ddtrace to run")
#         tracer.internal.writer.on_shutdown()
# except:
#     pass
