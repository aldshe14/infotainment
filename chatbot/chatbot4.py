
#!/usr/bin/python
import telepot
import MySQLdb
from telepot.loop import MessageLoop
import wget
import time
import sys
def handle(msg):
    content_type,chat_type,chat_id=telepot.glance(msg)
 #@#   print(content_type, chat_type, chat_id)
    command=msg['text']
    conn=MySQLdb.connect('localhost','prove','prove', 'prove')
    curs=conn.cursor()
    curs.execute("SELECT chat_id from users where role=1")
    variable=curs.fetchall()
    conn.commit()
    curs.execute("SELECT chat_id from users")
    variable1=curs.fetchall()
    conn.commit()
    #if variable1==variable:
        if command == '/users':
            curs.execute('Select chat_id from users')
            for result in curs.fetchall():
                bot.sendMessage(chat_id, result [0])
        #if command == '/block %s':
         #   curs.execute("insert into blacklist"\
          #          "(chat_id)" \
           #         "VALUES(%s)")
            #deksh=(
    else:
        bot.sendMessage(chat_id,'Hi, you are not the admin')
    if content_type == 'photo':
        curs.execute("SELECT chat_id from users where role=1")
        variable=curs.fetchall()
        conn.commit()
        curs.execute("SELECT chat_id from users")
        variable1=curs.fetchall()
        conn.commit()
        bot.sendMessage(chat_id, "Image received")
        print (msg)
        fileid=msg['photo'][2]['file_id']
        bot.sendPhoto(chat_id,fileid)
        print(fileid)
        file=bot.getFile(fileid)
        filepath = file['file_path']
        print (filepath)
        url= "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
        test='/home/pi/chatbot/%s' % (filepath)
        file1=wget.download(url,test) 
        curs.execute("Select chat_id from blacklist")
        variable3=curs.fetchall()
        conn.commit()
        curs.execute("Select chat_id from users")
        variable4=curs.fetchall()
        conn.commit()
        if variable4==variable3:
            bot.sendMessage(chat_id, "You're fucking blocked")
        else:
            insert_query =("insert into prove"\
                       "(prove_id,image_path)"\
                        "VALUES(%s, %s)")
            dickapalidhje=(0,file1)

            curs.execute(insert_query,dickapalidhje)
            conn.commit()
        
token= '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)
MessageLoop(bot, handle).run_as_thread()
print 'I am listening...'
while 1:
    time.sleep(10)

