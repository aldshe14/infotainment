#!/usr/bin/env python

import sys
import time
import telepot
from telepot.loop import MessageLoop
import wget

def handle(msg):
    content_type, chat_type, chat_id = telepot.glance(msg)
    print(content_type, chat_type, chat_id)

    if content_type == 'text':
        bot.sendMessage(chat_id, msg['text'])

    if content_type == 'photo':
    	bot.sendMessage(chat_id, "Image received")
        
        print (msg)
        fileid = msg['photo'][2]['file_id']
    	bot.sendPhoto(chat_id,fileid)
        print(fileid)
        file = bot.getFile(fileid)
        filepath = file['file_path']
        print (filepath)
        url = "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
        wget.download(url, '/home/pi/chatbot/test.jpg')
token = '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)
MessageLoop(bot, handle).run_as_thread()



print 'I am listening...'

while 1:
     time.sleep(10)
