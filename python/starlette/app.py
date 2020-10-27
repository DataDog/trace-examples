from starlette.applications import Starlette
from starlette.staticfiles import StaticFiles
from starlette.responses import HTMLResponse
from starlette.templating import Jinja2Templates
from starlette.config import Config
from starlette.routing import Route
import uvicorn
import sys
import databases
import sqlalchemy
import sqlite3
from sqlite3 import Error, Cursor, Connection

from ddtrace.contrib.starlette import patch as starlettePatch
from ddtrace.contrib.sqlalchemy import patch as sqlalchemyPatch

starlettePatch()
sqlalchemyPatch()
templates = Jinja2Templates(directory='templates')

def create_test_database(DATABASE_URL):
    engine = sqlalchemy.create_engine(DATABASE_URL)
    engine.execute("DROP TABLE IF EXISTS notes;")
    metadata = sqlalchemy.MetaData()
    notes = sqlalchemy.Table(
        "notes",
        metadata,
        sqlalchemy.Column("id", sqlalchemy.Integer, primary_key=True),
        sqlalchemy.Column("text", sqlalchemy.String),
        sqlalchemy.Column("completed", sqlalchemy.Boolean),
    )
    metadata.create_all(engine)


DATABASE_URL = "sqlite:///test.db"
create_test_database(DATABASE_URL)

database = databases.Database(DATABASE_URL)
engine = sqlalchemy.create_engine(DATABASE_URL)
metadata = sqlalchemy.MetaData(bind=engine, reflect=True)
notes_table = metadata.tables["notes"]


# Main application code.
async def homepage(request):
    template = "index.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context)


async def success(request):
    template = "200.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context, status_code=200)


async def error(request):
    """
    An example error. Switch the `debug` setting to see either tracebacks or 500 pages.
    """
    raise RuntimeError("Oh no")


async def not_found(request, exc):
    """
    Return an HTTP 404 page.
    """
    template = "404.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context, status_code=404)


async def server_error(request, exc):
    """
    Return an HTTP 500 page.
    """
    template = "500.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context, status_code=500)


async def list_notes(request):
    query = "SELECT * FROM NOTES"
    with engine.connect() as connection:
        result = connection.execute(query)
        d, a = {}, []
        for rowproxy in result:
            for column, value in rowproxy.items():
                d = {**d, **{column:value}}
            a.append(d)
    response = str(a)
    template = "200.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context, status_code=200)


async def add_note(request):
    request_json = await request.json()
    with engine.connect() as connection:
        with connection.begin():
            r = connection.execute(notes_table.select())
            connection.execute(notes_table.insert(), request_json)
    template = "200.html"
    context = {"request": request}
    return templates.TemplateResponse(template, context, status_code=200)


routes = [
    Route("/", endpoint=homepage, methods=["GET"]),
    Route("/200", endpoint=success, methods=["GET"]),
    Route("/500", endpoint=error, methods=["GET"]),
    Route("/notes", endpoint=list_notes, methods=["GET"]),
    Route("/notes", endpoint=add_note, methods=["POST"]),
]


app = Starlette(    
    routes=routes,
    on_startup=[database.connect],
    on_shutdown=[database.disconnect]
)

app.mount('/static', StaticFiles(directory='statics'), name='static')


if __name__ == "__main__":
    uvicorn.run(app, host='0.0.0.0', port=8000)
