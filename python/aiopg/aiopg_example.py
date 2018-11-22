import asyncio
import aiopg
import os

POSTGRES_CONFIG = {
    'host': 'localhost',
    'port': int(os.getenv('TEST_POSTGRES_PORT', 5432)),
    'user': os.getenv('TEST_POSTGRES_USER', 'postgres'),
    'password': os.getenv('TEST_POSTGRES_PASSWORD', 'postgres'),
    'dbname': os.getenv('TEST_POSTGRES_DB', 'postgres'),
}


dsn = 'dbname={dbname} user={user} password={password} host={host}'.format(**POSTGRES_CONFIG)


async def go():
    pool = await aiopg.create_pool(dsn)
    async with pool.acquire() as conn:
        async with conn.cursor() as cur:
            await cur.execute("SELECT 1")
            ret = []

loop = asyncio.get_event_loop()
loop.run_until_complete(go())
