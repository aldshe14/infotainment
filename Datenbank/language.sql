create table tb_infotainment_language(
l_id varchar(5) primary key not null, 
language varchar(50) not null
);

INSERT INTO `infotainment_system`.`tb_infotainment_language`
(`l_id`,
`language`)
VALUES
("en_en",
"English");


drop table tb_infotainment_language;

create table tb_infotainment_language(
l_id varchar(50) not null, 
language varchar(2) not null, 
translation varchar(250) not null,
primary key (l_id, language)
);