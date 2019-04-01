#!/usr/bin/env bash

set -e

./gradlew :spring-app:build

$@
