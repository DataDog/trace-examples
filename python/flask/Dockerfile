FROM  node:12-alpine
RUN  apk add --no-cache python2 g++ make
WORKDIR /home/ubuntu/idonate/trace-examples/python/flask
COPY . .
RUN yarn install --production
CMD ["python", "run", "python", "run.py"]


