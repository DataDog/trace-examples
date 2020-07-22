from sanic import Sanic, request, response
from sanic.response import json, html, text, HTTPResponse, json
import os


app = Sanic("Simple Sanic App")


@app.route("/")
async def test(request):
    return json({"hello": "world"})


@app.route("/html")
async def test(request):
    template = open(os.getcwd() + "/templates/index.html")
    return html(template.read())


@app.route("/text")
async def test(request):
    return text("This is Text Response.")


if __name__ == "__main__":
    app.run(host="0.0.0.0")
