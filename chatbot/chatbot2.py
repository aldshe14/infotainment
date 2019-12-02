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
        #url="https://api.telegram.org/bot960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g/getMe"
        path=wget.download(url, '/home/pi/chatbot/%s') % (filepath)
        conn=MySQLdb.connect('localhost','prove','prove', 'prove')
        curs=conn.cursor()
        insert_query =("insert into prove"\
                "(prove_id,image_path)"\
                "VALUES(%s)")
        curs.execute(insert_query,path)
        conn.commit()


token= '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)

MessageLoop(bot, handle).run_as_thread()
#signal.pause()
print 'I am listening...'
while 1:
    time.sleep(10)
