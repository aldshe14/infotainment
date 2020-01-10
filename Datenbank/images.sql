create table tb_infotainment_images(
	i_id int primary key auto_increment,
    name varchar(200) not null,
    image mediumblob not null,
    type varchar(10) not null
);

drop table tb_infotainment_images;