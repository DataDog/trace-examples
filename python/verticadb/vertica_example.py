import os
import vertica_python

from ddtrace import tracer


TEST_TABLE = "test_table"

VERTICA_CONFIG = {
    "host": os.getenv("TEST_VERTICA_HOST", "127.0.0.1"),
    "port": os.getenv("TEST_VERTICA_PORT", 5433),
    "user": os.getenv("TEST_VERTICA_USER", "dbadmin"),
    "password": os.getenv("TEST_VERTICA_PASSWORD", "abc123"),
    "database": os.getenv("TEST_VERTICA_DATABASE", "dbadmin"),
}


def connect_and_query_vertica():
    conn = vertica_python.connect(**VERTICA_CONFIG)
    cur = conn.cursor()

    with conn:
        cur.execute("DROP TABLE IF EXISTS {}".format(TEST_TABLE))
        cur.execute(
            """CREATE TABLE {} (
            a INT,
            b VARCHAR(32)
            )
            """.format(
                TEST_TABLE
            )
        )
        cur.execute(
            "INSERT INTO {} (a, b) VALUES (1, 'aa'); commit;".format(TEST_TABLE)
        )
        cur.execute("SELECT * FROM {};".format(TEST_TABLE))
        row = [i for i in cur.iterate()][0]
        assert row[0] == 1
        assert row[1] == "aa"


def main():
    print('querying vertica')
    with tracer.trace('vertica_example', service="vertica-example"):
        connect_and_query_vertica()


main()
