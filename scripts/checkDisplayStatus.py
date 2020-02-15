#!/usr/bin/python

import subprocess

for ping in range(1,10):
    address = "10.2.7." + str(ping)
    res = subprocess.call(['ping', '-c', '3', address])
    if res == 0:
        print "ping to", address, "OK"
    elif res == 2:
        print "no response from", address
    else:
        print "ping to", address, "failed!"


# import subprocess
# import os
# with open(os.devnull, "wb") as limbo:
#     for n in xrange(200, 240):
#             ip="10.2.7.{0}".format(n)
#             result=subprocess.Popen(["ping", "-n", "1", "-w", "200", ip],
#                     stdout=limbo, stderr=limbo).wait()
#             if result:
#                     print ip, "inactive"
#             else:
#                     print ip, "active"