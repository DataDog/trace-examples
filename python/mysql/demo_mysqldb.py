import logging.config
from ddtrace import Pin, patch
import MySQLdb

logging.config.dictConfig({
    'version': 1,
    'formatters': {
        'verbose': {
            'format': '%(levelname)s %(asctime)s %(module)s %(process)d %(thread)d %(message)s'
        },
    },
    'handlers': {
        'console': {
            'level': 'DEBUG',
            'class': 'logging.StreamHandler',
            'formatter': 'verbose',
        },
    },
    'loggers': {
        'ddtrace': {
            'handlers': ['console'],
            'level': 'DEBUG',
            'propagate': True,
        },
    }
})

# This will report a span with the default settings
conn = MySQLdb.connect(user="test", passwd="test", host="mysql", port=3306, db="test")
cursor = conn.cursor()
cursor.execute("SHOW TABLES")

# Use a pin to specify metadata related to this connection
Pin.override(conn, service='mysql-users')
