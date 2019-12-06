#!/usr/bin/python
import telepot
import MySQLdb
from telepot.loop import MessageLoop
import wget
import time
import sys
def handle(msg):
    content_type,chat_type,chat_id=telepot.glance(msg)
    print(content_type, chat_type, chat_id)
    if content_type == 'text':
        bot.sendMessage(chat_id, msg['text'])
    if content_type == 'photo':
        bot.sendMessage(chat_id, "Image received")
        print (msg)
        fileid=msg['photo'][2]['file_id']
        bot.sendPhoto(chat_id,fileid)
        print(fileid)
        file=bot.getFile(fileid)
        filepath = file['file_path']
        print (filepath)
        url= "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
        #url= "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
        #file1=wget.download(url,test)  
        test='/home/pi/chatbot/%s' % (filepath)
        file1=wget.download(url,test)  
        f = open(test, 'r')
        file_content = f.read()
        f.close()
        print test
        conn=MySQLdb.connect('localhost','prove','prove', 'prove')
        curs=conn.cursor()
        curs.execute("SELECT * from users where role=1")
        variable=curs.fetchall()
        conn.commit()
        curs.execute("SELECT chat_id from users where role=1")
        variable1=curs.fetchall()
        conn.commit()
        for variable1 in variable:

            insert_query =("insert into prove"\
                    "(prove_id,image_path)"\
                    "VALUES(%s, %s)")
            dickapalidhje=(0,file1)
            curs.execute(insert_query,dickapalidhje)
            conn.commit()
        else:
            bot.sendMessage(chat_id,'Hi, you are not the admin')
token= '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)

MessageLoop(bot, handle).run_as_thread()
#signal.pause()
print 'I am listening...'
while 1:
    time.sleep(10)
