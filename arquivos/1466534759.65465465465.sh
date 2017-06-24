#!/bin/bash
mkdir /home/bruno/Desktop/logs
for line in $(cat algs)
do
   mv /home/bruno/Desktop/out/dermatology/lang_en/$line/logs/relevance.log /home/bruno/Desktop/logs/lang_en$line.log
   mv /home/bruno/Desktop/out/dermatology/lang_pt/$line/logs/relevance.log /home/bruno/Desktop/logs/lang_pt$line.log
done
