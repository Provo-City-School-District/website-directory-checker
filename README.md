# Active User Directory Checker

Checks directory emails against another database to see that all emails are active.

you'll need to include your database credentials in a environment file. ```.env```

## Database 1 Variables<br>
VUSER=webserver
VPASS=
VLOC=158.91.5.51
VDATA=nas
$vUser = 1st database username<br>
$vPass = 1st database password<br>
$vLoc = 1st database location/ip address<br>
$vdata = 1st database name<br>

## Database 2 Variables<br>
$siteUser = 2nd database username<br>
$sitePass = 2nd database password<br>
$siteLoc = 2nd database location/ip address<br>
$siteDB = 2nd database name<br>

SITEUSER=publicAdmin
SITEPASS=
SITELOC=158.91.1.123
SITEDB=public
