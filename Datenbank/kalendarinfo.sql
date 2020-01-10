create table tb_infotainment_kalendarinfo(
	k_id int primary key auto_increment, 
	title varchar(100) not null,
    beschreibung varchar(250),
    datum date not null
);

drop table tb_infotainment_kalendarinfo;