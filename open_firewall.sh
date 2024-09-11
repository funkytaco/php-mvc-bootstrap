#!/bin/bash
sudo firewall-cmd --zone=ushr --add-port=8082/tcp --permanent
sudo firewall-cmd --reload
