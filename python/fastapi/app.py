from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()


class Item(BaseModel):
	id: int
	name: str
	description: str = None


@app.get("/")
async def read_root():
	return {"Hello": "World"}


@app.get("/items/{item_id}")
async def read_item(item_id: int, q: str = None):
	return {"item_id": item_id, "query": q}


@app.post("/items/", response_model=Item)
async def create_item(item: Item):
	return item
