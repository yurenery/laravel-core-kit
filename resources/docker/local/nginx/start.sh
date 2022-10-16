#!/bin/bash

export NAMESERVERS=$(cat /etc/resolv.conf | grep "nameserver" | awk '{print $2}' | tr '\n' ' ')

envsubst '$NAMESERVERS' < /var/www/docker/nginx.conf > /etc/nginx/nginx.conf

exec nginx -g "daemon off;"
