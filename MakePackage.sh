#!/bin/bash

tar -zcvf /home/martin/Desktop/Packages/$1.tar.gz --exclude='/home/martin/Documents/RabbitMQ.ini' --exclude='/home/martin/Documents/RabbitMQ.ini~' --exclude='/home/martin/Documents/loginBackup.sql' --exclude='/home/martin/Documents/log.txt' --exclude='/home/martin/Documents/log.txt~' /home/martin/Documents/*
