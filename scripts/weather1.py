#!/usr/bin/python

import mysql.connector

config = {
        'user':"infotainment",
        'password':"1nf0tainment",
        'host':"localhost",
}

con = mysql.connector.connect(**config)
cur = con.cursor(buffered=True)
cur.execute("Use infotainment_system")

data = cur.fetchone()
print "Database version : %s" % data

cur.close()
con.close()
