#!/bin/bash
echo "digite a senha";
read -s senha;
for line in $(seq -f "%02g" 1 25)
do
  echo "sshpass -p \"SENHA\" scp ./sudoers root@labamarelo${line}:/etc/sudoers"
  sshpass -p "${senha}" scp ./sudoers root@labamarelo${line}:/etc/sudoers
done
