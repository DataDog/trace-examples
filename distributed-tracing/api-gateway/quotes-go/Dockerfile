FROM golang:1.12 AS build
ADD *.go /go/src/quotes/
RUN go get -d quotes && go install quotes

FROM debian:stretch-slim
COPY --from=build /go/bin/quotes /bin/quotes
ENTRYPOINT ["/bin/quotes"]
