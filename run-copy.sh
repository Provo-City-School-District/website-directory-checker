#!/bin/bash

docker run --name dir-check --mount source=dir-check,target=/app/result dir-check

docker cp dir-check:/app/result/directory_report.txt ~/dir-check-results/

scp /home/webadmin/dir-check-results/directory_report.txt webupload@158.91.1.123:/var/www/html/public_html/wp-content/uploads/bad-emails/docker-directory_report.txt

docker rm dir-check