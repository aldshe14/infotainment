#!/usr/bin/python
import signal
from pprint import pprint
import telepot
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
    #f=open('.File.jpeg')
    query_id,from_id,query_data=telepot.glance(msg,flavor='callback_query')
    if(query_data=='press1'):
        bot.sendMessage(from_id, "Hallo")
    if(query_data=='press2'):
        bot.sendMessage(from_id,emoji)

        #file.download('image.jpg')

#def photo(message):
 #   print 'message.photo=', message.photo
  #  fileID = message.photo[-1].file_id
   # print 'fileID=', fileID
    #file_info=bot.get_file(fileID)
    #print 'file.file_path=', file_info.file_path
    #downloaded_file=bot.download_file(file_info.file_path)

    #with open("image.jpg", 'wb') as new_file:
        #new_file.write(downloaded_file)
def a(msg):
    content_type,chat_type,chat_id=telepot.glance(msg)
    pprint(msg)
    m=telepot.namedtuple.Message(**msg)
    if(content_type=='photo'):
        fileid=m.photo[0].file_id
        file=bot.getFile(fileid)
        file.download('dica.jpeg')
        bot.sendPhoto(chat_id,fileid)
        bot.download_file(msg['photo'][-1]['file_id'], '/home/pi/chatbot/file.png')
        bot.sendPhoto(chat_id,fileid)
        pprint("test")

       #bot.download_file(msg['photo'][-1]['file_id'], '/home/pi/chatbot/file.png')
  #  bot.download_file(msg['photo'][-1]['file_id'], './file.png')
   # print("file_id:" + str(update.message.photo.file_id))
    #file.download('image.jpg')
#def handle(msg):
 #   content_type, chat_type, chat_id = telepot.glance(msg)
  # if(content_type=='photo'):
   #    bot.download_file(msg['photo'][-1]['file_id'], '/home/pi/chatbot/file.png')
#updater.dispatcher.add_handler(MessageHandler(Filters.photo,image_handler))
#updater.start_polling()
#pdater.idle()

#MessageLoop(bot,{'a':image_handler}).run_as_thread()
MessageLoop(bot,{'chat':on_chat_message,'callback_query':on_callback_query,'handle':a}).run_as_thread()
signal.pause()
