import os

from cassandra.cluster import Cluster, ResultSet
from cassandra.query import BatchStatement, SimpleStatement



CASSANDRA_CONFIG = {
    'port': int(os.getenv('TEST_CASSANDRA_PORT', 9042)),
}


def setup():
    cluster = Cluster(port=CASSANDRA_CONFIG['port'])
    cluster.connect().execute('DROP KEYSPACE IF EXISTS test')
    cluster.connect().execute("CREATE KEYSPACE if not exists test WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor': 1}"  )
    cluster.connect().execute('CREATE TABLE if not exists test.person (name text PRIMARY KEY, age int, description text)')
    cluster.connect().execute('CREATE TABLE if not exists test.person_write (name text PRIMARY KEY, age int, description text)')
    # cluster.connect().execute("INSERT INTO test.person (name, age, description) VALUES ('Cassandra', 100, 'A cruel mistress')")
    # cluster.connect().execute("INSERT INTO test.person (name, age, description) VALUES ('Athena', 100, 'Whose shield is thunder')")
    # cluster.connect().execute("INSERT INTO test.person (name, age, description) VALUES ('Calypso', 100, 'Softly-braided nymph')")
    cluster.connect().execute("INSERT INTO test.person (name, age, description) VALUES ('Joe', 1, '好')")
    return cluster.connect()


def unicode_query(session):
    pass
    # query = 'INSERT INTO test.person_write (name, age, description) VALUES (%s, %s, %s)'
    # result = session.execute(SimpleStatement(query), ('Joe', 1, '好'))
    # print(result)


def main():
    session = setup()
    unicode_query(session)


if __name__ == '__main__':
    main()
