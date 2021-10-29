#!/usr/bin/env bash

dir=$(pwd)


# trap ctrl-c and call ctrl_c()
#trap ctrl_c INT

#ctrl_c () {
#      "$dir/mysql-5.7.20/bin/mysqladmin" -h 127.0.0.1 shutdown
#      exit;
#}

#start database
#--initialize-insecure \
#./mysql-5.7.20/bin/mysqld_safe \
#	--datadir="$dir/mysql-5.7.20/data/" \
#	--basedir="$dir/mysql-5.7.20" \
#	--skip-grant-tables \
#	--socket="/tmp/mysql.sock" \
#	--console \
#	--log-error="log.err" &

#start webserver
cd public/
php -dxdebug.remote_enable=1 \
-dxdebug.remote_autostart=1 \
-dxdebug.remote_mode=req \
-dxdebug.remote_port=9000 \
-dxdebug.remote_host=127.0.0.1 \
-S 0.0.0.0:9091 \
 ../server.php