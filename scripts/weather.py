#!/usr/bin/python

import MySQLdb

conn = MySQLdb.connect("localhost","infotainment","1nf0tainment","infotainment_system")

cur = conn.cursor()
data = ""
try:
    cur.execute("select * from tb_infotainment_users")
    #conn.comit()
    data = cur.fetchone()
    conn.comit()
except:
    conn.rollback()
print "Database version : %s" % data[1]

conn.close()
