#!/usr/bin/python
import os

lines=('10.2.7.10','10.2.7.1')

for line in lines:
    response=os.system("ping -c 1 " + line)
    if (response == 0):
        status = line.rstrip() + " is Reachable"
    else:
        status = line + " is Not reachable"
    print(status)
