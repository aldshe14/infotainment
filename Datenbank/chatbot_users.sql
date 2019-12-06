use infotainment_system;
create table tb_infotainment_chatbot_users(
c_id int primary key, 
user_status int(1), 
role int(3),
constraint fk_chatbotuser_roles 
foreign key (role) references tb_infotainment_roles(r_id) 
);