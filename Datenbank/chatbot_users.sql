use infotainment_system;
create table tb_infotainment_chatbot_users(
c_id int primary key, 
user_status int(1), 
role int(3),
telefonnummer varchar(255), 
checked int(1),
constraint fk_chatbotuser_roles 
foreign key (role) references tb_infotainment_roles(r_id) 
);

drop table tb_infotainment_chatbot_users;

insert into tb_infotainment_chatbot_users(c_id, user_status, role, telefonnummer, checked) Values(609813737, 1, 777, '0699391374', 1),
(707072293, 1, 777, '0675304547', 0);

delete from tb_infotainment_chatbot_users where role =555;

select * from tb_infotainment_chatbot_users;