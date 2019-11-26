#!/usr/bin/env python

import sys
import time
import random
import datetime
import telepot


def handle(msg):
    chat_id = msg['chat']['id']
    command = msg['text']

    print 'Got command: %s' % command

    if True:
        bot.sendMessage(chat_id,text="Button Pressed")
        time.sleep(0.2)
    

bot = telepot.Bot('960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g')
bot.message_loop(handle)
print 'I am listening...'

while 1:
     time.sleep(10)