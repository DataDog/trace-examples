FROM golang:1.12 AS build
ADD *.go /go/src/frontend/
RUN go get -d frontend && go install frontend

FROM debian:stretch-slim
COPY --from=build /go/bin/frontend /bin/frontend
ENTRYPOINT ["/bin/frontend"]
