# Active User Directory Checker

build container with ```docker build -t dir-check .``` inside the directory

run with ```docker run --name dir-check  dir-check ```

copy the results file with ```docker cp dir-check:/app/directory_report.txt ~/Downloads```

you'll need to provide a .env file with the following variables
```
VUSER=
VPASS=
VLOC=
VDATA=
SITEUSER=
SITEPASS=
SITELOC=
SITEDB=
```