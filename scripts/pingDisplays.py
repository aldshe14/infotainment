#!/usr/bin/python
import os

hostsfile=open("hosts", "r")

lines=hostsfile.readlines()

for line in lines:
    response=os.system("ping -c 1 " + line)
    if (response == 0):
        status = line.rstrip() + " is Reachable"
    else:
        status = line + " is Not reachable"
    print(status)