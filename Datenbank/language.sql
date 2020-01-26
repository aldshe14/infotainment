create table tb_infotainment_language(
l_id varchar(50) not null, 
language varchar(2) not null, 
translation varchar(250) not null,
primary key (l_id, language)
);
