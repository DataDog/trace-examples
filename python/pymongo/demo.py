import logging.config
import pymongo
from ddtrace import patch, Pin

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


if __name__ == '__main__':
    patch(pymongo=True)
    client = pymongo.MongoClient()
    pin = Pin.override(client, service="mongo-master----------------s")
    db = client.test_database
    collection = db.test_collection
    collection.insert_one({"name": "Luca"})
