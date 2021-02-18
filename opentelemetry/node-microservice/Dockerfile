FROM node:latest
WORKDIR /app
COPY package.json /app
RUN npm install
COPY . /app
EXPOSE 3000
RUN npm install -g nodemon
CMD [ "nodemon","index.js" ]
