from influxdb.client import InfluxDBClient
from ddtrace.contrib.influx import patch


DB_NAME = 'test_db'


def main():
    patch()
    client = InfluxDBClient(host='127.0.0.1', port=8086, database=DB_NAME)
    client.create_database(DB_NAME)

    json_body = [
        {
            "measurement": "cpu_load_short",
            "tags": {
                "host": "server01",
                "region": "us-west"
            },
            "time": "2009-11-10T23:00:00Z",
            "fields": {
                "value": 0.64
            }
        }
    ]

    client.write_points(json_body)

    result = client.query('select value from cpu_load_short;')

    print("Result: {0}".format(result))

    client.close()

if __name__ == '__main__':
    main()
