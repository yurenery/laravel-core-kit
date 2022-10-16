#!/bin/bash

VAULT_ADDR=$1||false
VAULT_TOKEN=$2||false
VAULT_PATH=$3||false
ERASE_OLD_ENV=${4:-1}
VAULT_ENV_FILE=${5:-.env.vault}

if [ "$ERASE_OLD_ENV" == "1" ] && [ -f $VAULT_ENV_FILE ]; then
  echo "Old $VAULT_ENV_FILE removed."
  rm $VAULT_ENV_FILE
fi

if [ $VAULT_TOKEN ] && [ $VAULT_ADDR ] && [ $VAULT_PATH ]; then
  echo "Login to vault $VAULT_ADDR"
  vault login $VAULT_TOKEN
  echo "Logged in..."

  echo "Parsing vault env into $VAULT_ENV_FILE"
  v_values=`vault kv get -format=json $VAULT_PATH`
  j_values=`echo $v_values | jq -r .data.data`
  while read -rd $'' line
  do
    export "$line"
    echo $line >> $VAULT_ENV_FILE
  done < <(jq -r <<<"$j_values" 'to_entries|map("\(.key)=\(.value)\u0000")[]')
else
  echo "Vault token or address is invalid. Script dying..."
  exit 1;
fi