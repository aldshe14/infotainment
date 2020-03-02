create table tb_infotainment_supplieren(
	u_id int,
    woche int,
    supplierer varchar(3) not null,
    beschreibung varchar(50),
    primary key(u_id,woche)
);

-- create table tb_infotainment_supplieren(
--    u_id int,
--    woche int,
--    supplierer varchar(3) not null,
--    beschreibung varchar(50),
--    primary key(u_id,woche),
--    constraint fk_supplieren_fehlendelehrer
--    foreign key(u_id)
--    references tb_infotainment_fehlendelehrer(u_id)
-- );


drop table tb_infotainment_supplieren;