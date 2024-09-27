#!/bin/bash

docker run --name dir-check --mount source=dir-check,target=/app/result dir-check

docker rm dir-check