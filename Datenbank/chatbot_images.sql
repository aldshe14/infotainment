create table tb_infotainment_chatbot_images(
	i_id int primary key auto_increment,
    image mediumblob not null,
    type varchar(10) not null,
    datum datetime not null default now(),
    u_id int not null,
    constraint fk_chatbotimages_user
    foreign key (u_id)
    references tb_infotainment_chatbot_users(c_id)
);

drop table tb_infotainment_chatbot_images;