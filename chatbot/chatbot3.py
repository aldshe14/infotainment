#!/usr/bin/python
import telepot
import MySQLdb
from telepot.loop import MessageLoop
#import wget
import urllib2 
# pip install urllib2_file
import time
import sys
from telepot.namedtuple import InlineKeyboardMarkup, InlineKeyboardButton 


conn=MySQLdb.connect('localhost','infotainment', '1nf0tainment', 'infotainment_system')
curs=conn.cursor()
button=""
register=""


def handle(msg):
    content_type,chat_type,chat_id=telepot.glance(msg) 
    keyboard=InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Po', callback_data='press1'), InlineKeyboardButton(text='Jo', callback_data='press2')],])    
    #query_id, from_id, query_data=telepot.glance(msg, flavor=flavor)
    username=msg['from']['first_name']

    print chat_id
    if content_type == 'text':
        command=msg['text']
    elif content_type == 'photo':
        command=msg['photo']
    print "Test"
    global button
    print button
    if button == 'pressed':
        reply=msg["text"]
        if reply.isdigit()==True:
            if(len(reply)==10):
                if reply[0] ==0 and reply[1]==6 and reply[2]==7 or reply[2]==9:
                    print reply
                    query3=("insert into tb_infotainment_chatbot_users" \
                    "(c_id, user_status, role, telefonnummer, checked)"  
                    "VALUES(%s, %s, %s, %s, %s)")
                    execute=(chat_id,1,555,reply,0)
                    curs.execute(query3,execute)
                    conn.commit()
                    global button
                    button = ""
                    bot.sendMessage(chat_id, "Great! Your data has been sent to the administrator!")
                    query_kontrolle=("Select c_id, checked from tb_infotainment_chatbot_users")
                    execute_kontrolle=curs.execute()
                    notchecked=curs.fetchall()
                else:
                    bot.sendMessage(chat_id, "Invalid phone number format")
                #if notchecked[1] == 2:
                 #   bot.sendMessage(chat_id, "Administrator hasn't confirmed you")
                #elif notchecked[1] == 1:
                 #   bot.sendMessage(chat_id, "Congratulations, administrator has confirmed you")
            else:
                bot.sendMessage(chat_id, "The length of your phone number is not correct")
                global register
                register = "true"
        else:
            bot.sendMessage(chat_id, "Invalid phone number format")
    print username
    query=("SELECT c_id, role, user_status, checked, telefonnummer from tb_infotainment_chatbot_users where c_id = %s") %(int(chat_id))
    count=curs.execute(query)
    global register
    if count > 0:
        user=curs.fetchone()
        

        if user[1] == 777 and user[3]==1:
            if command == '/users':
                curs.execute('SELECT c_id, telefonnummer, (row_number() over (order by c_id)) from tb_infotainment_chatbot_users')
                variable1=curs.fetchall()
                users2=""
                for users in variable1:
                    users2+=(str(users[2])+" "+str(users[0])+" "+str(users[1])+"\n")
                    bot.sendMessage(chat_id, users2)
            if command == '/see_unregistered_users':
                curs.execute("Select c_id, telefonnummer, (row_number() over (order by c_id)) from tb_infotainment_chatbot_users where checked=0")
                unconfirmed=curs.fetchall()
                users=""
                for user in unconfirmed:
                    users+=(str(user[2])+" "+str(user[0])+" "+str(user[1])+"\n")
                bot.sendMessage(chat_id,users)
            DoNotAccept=command.split()
            #if DoNotAccept[0] == '/DoNotAccept':
             #   confirmationquery=("update tb_infotainment_chatbot_users set checked=2 where c_id= %s") %(int(DoNotAccept[1]))
              #  curs.execute(confirmationquery)
               # conn.commit()
            block=command.split()
            if  block[0]== '/block':
                updatequery =("update tb_infotainment_chatbot_users set user_status='0' where c_id= %s") %(int(block[1]))
                curs.execute(updatequery)
                conn.commit()
                bot.sendMessage(chat_id, "This user has been blocked")
        elif user[1] == 555 and user[3]==1:
            if command == '/users':
                bot.sendMessage(chat_id,'Hi, you are not the admin')
            if content_type == 'text':
                bot.sendMessage(chat_id, 'Hi')
            if content_type == 'photo':
                if user[2]== 0:
                    bot.sendMessage(chat_id, "Your images will not be displayed anymore")
                else:
                    bot.sendMessage(chat_id, "Image received")
                    print (msg)
                    fileid=msg['photo'][2]['file_id']
                    #bot.sendPhoto(chat_id,fileid)
                    print(fileid)
                    file=bot.getFile(fileid)
                    filepath = file['file_path']
                    print (filepath)
                    url= "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
                    filedata = urllib2.urlopen(url)
                    blobImage = filedata.read()
                    #test='/home/pi/chatbot/%s' % (filepath)
                    #file1=wget.download(url,test) 
                    insert_query =("insert into tb_infotainment_chatbot_images"\
                       "(image, u_id)"\
                        "VALUES(%s, %s)")
                    values=(blobImage,chat_id)

                    curs.execute(insert_query,values)
                    conn.commit()

        else:
            bot.sendMessage(chat_id, "Waiting for confirmation")
    elif register=="true":
        bot.sendMessage(chat_id, "Write your phone number again")
    else:
        bot.sendMessage(chat_id, "You're not registered")
        bot.sendMessage(chat_id, "Do you want to register?")
        bot.sendMessage(chat_id, 'Inline Keyboard', reply_markup=keyboard)

def on_callback_query(msg):
    query_id, from_id, query_data=telepot.glance(msg,flavor='callback_query')
    bot.answerCallbackQuery(query_id, text='getIt')
    print(query_data)
    if query_data=='press1':
         bot.sendMessage(from_id,text="ok")
         query2=("Select c_id from tb_infotainment_chatbot_users where c_id =%s") %(int(from_id))
         count1=curs.execute(query2)
         if count1 > 0:
             bot.sendMessage(from_id, text="You have been registered")
         else:
             bot.sendMessage(from_id, text="Please write your phone number below")
             #bot.register_next_step_handler(msg, process_name_step)
             
             
             
             global button
             button="pressed"
             #print "irenamoj"

    else:
         bot.sendMessage(from_id, text="nvm")


token= '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)
MessageLoop(bot, {'chat':handle, 'callback_query':on_callback_query}).run_as_thread()
print 'I am listening...'
while 1:
    time.sleep(10)
