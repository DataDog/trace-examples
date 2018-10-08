import os
import vertica_python
from vertica_python.errors import VerticaSyntaxError

from ddtrace import tracer


TEST_TABLE = "test_table"

VERTICA_CONFIG = {
    "host": os.getenv("TEST_VERTICA_HOST", "127.0.0.1"),
    "port": os.getenv("TEST_VERTICA_PORT", 5433),
    "user": os.getenv("TEST_VERTICA_USER", "dbadmin"),
    "password": os.getenv("TEST_VERTICA_PASSWORD", "abc123"),
    "database": os.getenv("TEST_VERTICA_DATABASE", "dbadmin"),
}


def invalid_query():
    conn = vertica_python.connect(**VERTICA_CONFIG)
    cur = conn.cursor()

    with conn:
        try:
            cur.execute("INVALID QUERY")
        except VerticaSyntaxError:
            pass


def query():
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


def fetching():
    conn = vertica_python.connect(**VERTICA_CONFIG)
    cur = conn.cursor()
    with conn:
        cur.execute(
            """
            INSERT INTO {} (a, b)
            SELECT 1, 'a'
            UNION ALL
            SELECT 2, 'b'
            UNION ALL
            SELECT 3, 'c'
            UNION ALL
            SELECT 4, 'd'
            UNION ALL
            SELECT 5, 'e'
            """.format(TEST_TABLE)
        )
        assert cur.rowcount == -1

        cur.execute("SELECT * FROM {};".format(TEST_TABLE))
        cur.fetchone()
        cur.rowcount == 1
        cur.fetchone()
        cur.rowcount == 2
        cur.fetchall()
        cur.rowcount == 5


def main():
    print('querying vertica')
    with tracer.trace('vertica_example', service="vertica-example"):
        query()

    with tracer.trace('vertica_example_error', service="vertica-example"):
        invalid_query()

    with tracer.trace('vertica_example_fetching', service="vertica-example"):
        fetching()


main()
