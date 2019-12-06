#!/usr/bin/python
import signal
from pprint import pprint
import telepot
import MySQLdb
from telepot.loop import MessageLoop
import telepot.namedtuple
from telepot.namedtuple import InlineKeyboardMarkup,InlineKeyboardButton
bot=telepot.Bot('960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g')
pprint(bot.getMe())
def on_chat_message(msg):
    pprint(msg)
    content_type,chat_type,chat_id=telepot.glance(msg)
    keyboard=InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Press me',callback_data='press1')],[InlineKeyboardButton(text='Press me',callback_data='press2')],])
    bot.sendMessage(chat_id,'Inline Keys', reply_markup=keyboard)
def on_callback_query(msg):
    emoji=u'\U0001f60d'
    query_id,from_id,query_data=telepot.glance(msg,flavor='callback_query')
    if(query_data=='press1'):
        bot.sendMessage(from_id, "Hallo")
    if(query_data=='press2'):
        bot.sendMessage(from_id, "hahhahaaaaaa")
    myvar=a(msg)
    conn=MySQLdb.connect('localhost','prove','prove', 'prove')
    curs=conn.cursor()
    insert_query = """Insert into prove(prove_id,image_path)
     #           Values (%s, %s) """
    dickapalidhje=(1,myvar)
    curs.execute(insert_query,dickapalidhje)
    conn.commit()
def a(msg): 
    content_type,chat_type,chat_id=telepot.glance(msg)
    m=telepot.namedtuple.Message(**msg)
    if(content_type=='photo'):
        fileid=m.photo[0].file_id
        file=bot.getFile(fileid)
        bot.sendPhoto(chat_id,fileid)
        file1= bot.download_file(msg['photo'][-1]['file_id'], '/home/pi/chatbot/file.png')
        return file1

MessageLoop(bot,{'chat':on_chat_message,'callback_query':on_callback_query,'a':a}).run_as_thread()
signal.pause()
