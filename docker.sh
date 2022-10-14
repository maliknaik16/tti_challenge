
#!/bin/bash

docker run --name tti -e MYSQL_ROOT_PASSWORD=1234 -e MYSQL_DATABASE=tti -p 3306:3306 -d mysql:8.0.31
