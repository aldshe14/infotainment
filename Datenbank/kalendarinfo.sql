create table tb_infotainment_kalendarinfo(
	k_id int primary key, 
	title varchar(100) not null,
    beschreibung varchar(250),
    begin datetime not null,
    end datetime
);