#!/usr/bin/python
import os
import MySQLdb

conn=MySQLdb.connect('localhost','infotainment', '1nf0tainment', 'infotainment_system')
curs=conn.cursor()

query=("Select d_id,ip from tb_infotainment_display")
curs.execute(query)
displays=curs.fetchall()

for displays in display:
    response=os.system("ping -c 1 " + display[1])
    status=0
    if (response == 0):
        status = 1

    query=("Select d_id,ip from tb_infotainment_display d join tb_infotainment_display_status ds on d.d_id = ds.d_id where d_id = %s ")
    param=(display[0])
    curs.execute(query,param)
    result=curs.fetchall()

    if (result):
        update=("UPDATE tb_infotainment_display_status SET status=%s where d_id=%s")
        param=(status,display[0])
        curs.execute(update,param)
        conn.commit()
    else:
        insert=("INSERT INTO tb_infotainment_display_status(d_id,status,lastChecked) VALUES(%s,%s,now())")
        param=(display[0],status)
        curs.execute(insert,param)
        conn.commit()
