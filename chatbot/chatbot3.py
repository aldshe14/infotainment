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
#global button
button="1"
register=""
global language
language=1
multilanguage=("select * from chatbotMultiLanguage")
curs.execute(multilanguage)
txt=curs.fetchall()
defaultadminmsgen = "Hi admin, below the following options:\n/users\n/see_unregistered_users\n/Accept\n/DoNotAccept\n/block\n/setLanguage DE|EN"
defaultadminmsgde= "Hallo Administrator, unten stehen die folgenden Optionen:\n/users\n/see_unregistered_users\n/Accept\n/DoNotAccept\n/block\n/setLanguage DE|EN"
defaultusermsgen = "Hi User, \nbelow the following options:\n-You can send an image and this image will be automatically displayed on the screen.\n/setLanguage DE|EN"
defaultusermsgde="Hallo Benutzer, \nunten stehen die folgenden Optionen:\n/-Sie koennen ein Bild schicken und es wird am Bildschirm dargestellt.\n/setLanguage DE|EN"
defaultunregistrierten="Hi, This is the Infotainment System. Below the following options:\n/register\n/setLanguage DE|EN"
defaultunregistriertde="Hallo, Willkommen zu Infotainment System. Unten stehen die folgenden Optionen:\n/register\n/setLanguage DE|EN"
sprache="Type EN for english and DE for german"
def handle(msg):
    content_type,chat_type,chat_id=telepot.glance(msg) 
    keyboard=InlineKeyboardMarkup(inline_keyboard=[[InlineKeyboardButton(text='Yes', callback_data='press1'), InlineKeyboardButton(text='No', callback_data='press2')],])    
    #query_id, from_id, query_data=telepot.glance(msg, flavor=flavor)
    username=msg['from']['first_name']

    print chat_id
    if content_type == 'text':
        command=msg['text']
    elif content_type == 'photo':
        command=msg['photo']
    print "TTT"
    global button
    print button
    if button == 'pressed':
        reply=msg["text"]
        lastcharacter=len(reply)-1
        string=reply[1:lastcharacter]
        print "TESTTTTTT"
        print reply
        if reply[0]=='+' and string.isdigit()==True:
            if(len(string)>=10) and (len(string)<=12):
                    print reply
                    query3=("insert into tb_infotainment_chatbot_users" \
                    "(c_id, user_status, role, telefonnummer, checked)"  
                    "VALUES(%s, %s, %s, %s, %s)")
                    execute=(chat_id,1,555,reply,0)
                    curs.execute(query3,execute)
                    conn.commit()
                    global button
                    button = ""
                    bot.sendMessage(chat_id, txt[0][language])
                    query_kontrolle=("Select c_id, checked from tb_infotainment_chatbot_users")
                    execute_kontrolle=curs.execute(query_kontrolle)
                    notchecked=curs.fetchall()
                    if notchecked[1] == 2:
                        bot.sendMessage(chat_id, txt[15][language])
                    elif notchecked[1] == 1:
                        bot.sendMessage(chat_id, txt[16][language])
                    global register
                    register="true"
            else:
                bot.sendMessage(chat_id, txt[3][language])
        else:
            bot.sendMessage(chat_id, txt[1][language])
    print username
    query=("SELECT c_id, role, user_status, checked, telefonnummer from tb_infotainment_chatbot_users where c_id = %s") %(int(chat_id))
    count=curs.execute(query)
    global register
    if count > 0:
        user=curs.fetchone()
        if user[1] == 777 and user[3]==1:
            usermng=command.split()

            if command=='/info':
                if language==1:
                    bot.sendMessage(chat_id,defaultadminmsgen)
                else:
                    bot.sendMessage(chat_id,defaultadminmsgde)
            
            elif usermng[0] == '/setLanguage':
                global language
                language=1
                if usermng[1]=='EN':
                    language=1
                    bot.sendMessage(chat_id, txt[12][language])
                elif usermng[1]=='DE':
                    language=2
                    bot.sendMessage(chat_id, txt[12][language])
                else:
                    bot.sendMessage(chat_id, txt[17][language])
                #bot.sendMessage(chat_id, txt[12][language])
            elif usermng[0]=='/Accept':
                acceptquery=("update tb_infotainment_chatbot_users set checked=1 where c_id=%s") %(int(usermng[1]))
                curs.execute(acceptquery)
                conn.commit()
                bot.sendMessage(usermng[1], txt[16][language])
                bot.sendMessage(chat_id, txt[18][language])
            
            elif usermng[0]=='/DoNotAccept':
                donotacceptquery=("update tb_infotainment_chatbot_users set checked=2 where c_id=%s") %(int(usermng[1]))
                curs.execute(donotacceptquery)
                conn.commit()
                bot.sendMessage(usermng[1],txt[15][language])
                bot.sendMessage(chat_id, txt[20][language])
            
            elif command == '/users':
                userscommand=('SELECT c_id, telefonnummer, (row_number() over (order by c_id)), role, checked, user_status  from tb_infotainment_chatbot_users')
                count4=curs.execute(userscommand)
                variable1=curs.fetchall()
                if count4> 0:
                    users2=""
                    for users in variable1:
                        rolle=""
                        if users[3]=='555' and users[4]==1:
                            rolle="Benutzer"
                        elif users[3]=='555' and users[4]==0:
                            rolle="Unregistered"
                        elif users[3]=='777':
                            rolle="Administrator"
                        elif users[5]=='1' and users[2]==555:
                            rolle="Blockiert"
                        users2+=(str(users[2])+"|"+" userid:  "+str(users[0])+" telefon: "+str(users[1])+"\n")
                    bot.sendMessage(chat_id, txt[19][language])
                    bot.sendMessage(chat_id, users2)
                else:
                    bot.sendMessage(chat_id, txt[21][language])
            elif command == '/see_unregistered_users':
                unregisteredcommand=("Select c_id, telefonnummer, (row_number() over (order by c_id)) from tb_infotainment_chatbot_users where checked=0")
                count5=curs.execute(unregisteredcommand)
                unconfirmed=curs.fetchall()
                if count5 > 0:
                    users=""
                    for user in unconfirmed:
                        users+=(str(user[2])+"|"+" userid: "+str(user[0])+" telefon: "+str(user[1])+"\n")
                    bot.sendMessage(chat_id, txt[22][language])
                    bot.sendMessage(chat_id,users)
                else:
                    bot.sendMessage(chat_id, txt[23][language])
            elif usermng[0]== '/block':
                updatequery =("update tb_infotainment_chatbot_users set user_status='1' where c_id= %s") %(int(usermng[1]))
                curs.execute(updatequery)
                conn.commit()
                bot.sendMessage(chat_id, txt[24][language])
            else:
                if language==1:
                    bot.sendMessage(chat_id, "Hi Administrator. That is not a valid command. Please try /info.")
                else:
                    bot.sendMessage(chat_id, "Hallo Administrator. Dieser Befehl ist nicht valid. Bitte probieren Sie /info aus.")
        elif user[1] == 555 and user[3]==1:
            if content_type == 'text':
                usermsg=command.split()
                if command=='/info':
                    if language==1:
                        bot.sendMessage(chat_id,defaultusermsgen)
                    else:
                        bot.sendMessage(chat_id,defaultusermsgde)
                elif usermsg[0] == '/setLanguage':
                    global language
                    language=1
                    if usermsg[1]=='EN':
                        language=1
                        bot.sendMessage(chat_id, txt[12][language])
                    elif usermsg[1]=='DE':
                        language=2
                        bot.sendMessage(chat_id, txt[12][language])
                    else:
                        bot.sendMessage(chat_id, txt[17][language])
                else:
                    if language==1:
                        bot.sendMessage(chat_id, "Hi User. That is not a valid command. Please try /info.")
                    else:
                        bot.sendMessage(chat_id, "Hallo Benutzer. Dieser Befehl ist nicht valid. Bitte probieren Sie /info aus.")
            else:
                    if user[2]== 1:
                        bot.sendMessage(chat_id, txt[5][language])
                    else:
                        bot.sendMessage(chat_id, txt[6][language])
                        print (msg)
                        fileid=msg['photo'][2]['file_id']
                        print(fileid)
                        file=bot.getFile(fileid)
                        filepath = file['file_path']
                        print (filepath)
                        url= "https://api.telegram.org/file/bot%s/%s" % (token,filepath)
                        filedata = urllib2.urlopen(url)
                        blobImage = filedata.read()
                        insert_query =("insert into tb_infotainment_chatbot_images"\
                        "(image, u_id)"\
                            "VALUES(%s, %s)")
                        values=(blobImage,chat_id)

                        curs.execute(insert_query,values)
                        conn.commit()

        else:
            bot.sendMessage(chat_id, txt[7][language])
    elif register=="true":
        bot.sendMessage(chat_id, txt[8][language])
    else:   
            usermlg=command.split()
            if command=='/info':
                if language==1:
                    bot.sendMessage(chat_id,defaultunregistrierten)
                else:
                    bot.sendMessage(chat_id,defaultunregistriertde)
            
            elif usermlg[0] == '/setLanguage':
                global language
                language=1
                if usermlg[1]=='EN':
                    language=1
                    bot.sendMessage(chat_id, txt[12][language])
                elif usermlg[1]=='DE':
                    language=2
                    bot.sendMessage(chat_id, txt[12][language])
                else:
                    bot.sendMessage(chat_id, txt[17][language])
            elif command == '/register':
                bot.sendMessage(chat_id, txt[9][language])
                bot.sendMessage(chat_id, txt[10][language])
                bot.sendMessage(chat_id,'Inline Keyboard',reply_markup=keyboard)
            else:
                if language==1:
                    bot.sendMessage(chat_id, 'This is not a valid command. Please try /info')
                else:
                    bot.sendMessage(chat_id, 'Dieser Befehl ist nicht valid. Bitte probieren Sie /info aus')

def on_callback_query(msg):
    query_id, from_id, query_data=telepot.glance(msg,flavor='callback_query')
    bot.answerCallbackQuery(query_id, text='getIt')
    print(query_data)
    if query_data=='press1':
         #bot.sendMessage(from_id,text="ok")
         query2=("Select c_id from tb_infotainment_chatbot_users where c_id =%s") %(int(from_id))
         count1=curs.execute(query2)
         if count1 > 0:
             if language==1:
                 bot.sendMessage(from_id, text="You have been registered")
             else:
                 bot.sendMessage(from_id, text="Sie sind schon registriert")
         else:
            if language==1:
                bot.sendMessage(from_id, text="Please write your phone number below.")
            else:
                bot.sendMessage(from_id, text="Bitte schreiben Sie Ihre Telefonnummer.")
                #bot.register_next_step_handler(msg, process_name_step)
                global button
                button="pressed"
             #print "irenamoj"

    else:
         bot.sendMessage(from_id, text="OK!")


token= '960505913:AAG5EqEksnoiXLZj0C-VmZ9UdbvjuSvDg1g'
bot = telepot.Bot(token)
MessageLoop(bot, {'chat':handle, 'callback_query':on_callback_query}).run_as_thread()
print 'I am listening...'
while 1:
    time.sleep(10)

