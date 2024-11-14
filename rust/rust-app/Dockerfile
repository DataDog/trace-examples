# syntax=docker/dockerfile:1.5

# Keep version in sync with rust-toolchain.toml
FROM rust:1.80.1-slim-bullseye

WORKDIR /app

COPY . .

RUN --mount=type=cache,target=/usr/local/cargo/registry \
    --mount=type=cache,target=/app/target \
    cargo build --release && cp target/release/rust-app /app/rust-app

ENTRYPOINT ["/app/rust-app"]
