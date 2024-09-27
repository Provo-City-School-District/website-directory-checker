#!/bin/bash

GRAYLOG_SERVER="158.91.5.82"
GRAYLOG_PORT="10517"

docker run --name dir-check \
  --log-driver=gelf \
  --log-opt gelf-address=udp://$GRAYLOG_SERVER:$GRAYLOG_PORT \
  dir-check

docker rm dir-check