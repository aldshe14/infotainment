create table tb_infotainment_layout(
	l_id int primary key auto_increment,
    name varchar(50) not null,
    beschreibung varchar(250) not null,
    file varchar(50) not null,
    icon mediumblob not null
);

drop table tb_infotainment_layout;