create table tb_infotainment_password_reset(
	p_id int primary key auto_increment,
    u_id int not null,
    datum datetime default now() not null,
    id varchar(250) not null,
    expire datetime default now() not null
);

drop table tb_infotainment_password_reset;