#!/bin/bash
echo "Replica reboot monitoring started...."

OLD_REPLICA=""

while :
do
	CURRENT_REPLICA=$(nslookup $PHP_SERVICE_NAME 127.0.0.11 | grep $PHP_SERVICE_NAME.$TASK_SLOT | awk '{print $4}')
	#echo "CURRENT: $CURRENT_REPLICA";
	#echo "OLD: $OLD_REPLICA";
	if [ "$CURRENT_REPLICA" != "" ] && [ "$OLD_REPLICA" != "$CURRENT_REPLICA" ]; then
	    OLD_REPLICA=$CURRENT_REPLICA;
	    sleep 1;
	    kill $(ps aux | grep 'nginx: master' | awk '{print $1}');
	    echo "FPM_HOST exported: $CURRENT_REPLICA";
	fi
    sleep 3;
    #echo "Wake up-------------------";
done