# Active User Directory Checker

build container with ```docker build -t dir-check .``` inside the directory

run with 
```bash
/usr/bin/docker run --rm \
  --log-driver=syslog --log-opt syslog-address=udp://localhost:514 \
  --log-opt tag=dir-check \
  --name dir-check --mount source=dir-check,target=/app/result dir-check \
  >> /home/webadmin/dir-check.log 2>&1 && \
  date > /home/webadmin/website-directory-checker-lastrun.txt
```

you'll need to provide a .env file with the following variables
```
VUSER=
VPASS=
VLOC=
VDATA=
VDPORT=
SITEUSER=
SITEPASS=
SITELOC=
SITEDB=
SITEPORT=
```