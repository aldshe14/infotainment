create table tb_infotainment_password_reset(
	p_id int primary key auto_increment,
    u_id int not null,
    datum datetime default now(),
    id varchar(250),
    expire datetime default DATE_ADD(now(),INTERVAL 24 HOUR)
);

drop table tb_infotainment_password_reset;