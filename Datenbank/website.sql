create table tb_infotainment_website_posts(
	w_id int primary key auto_increment,
    title varchar(200) not null,
    content text not null,
    image mediumblob not null,
    datum datetime not null
);

drop table tb_infotainment_website_posts;