#!/usr/bin/env python

import sys
import time
import telepot
from telepot.loop import MessageLoop

def handle(msg):
    content_type, chat_type, chat_id = telepot.glance(msg)
    print(content_type, chat_type, chat_id)

    if content_type == 'text':
        bot.sendMessage(chat_id, msg['text'])

    if content_type == 'photo':
    	bot.sendMessage(chat_id, "Image received")

bot = telepot.Bot('960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g')

MessageLoop(bot, handle).run_as_thread()



print 'I am listening...'

while 1:
     time.sleep(10)