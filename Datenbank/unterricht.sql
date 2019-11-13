drop table tb_infotainment_unterricht;

create table tb_infotainment_unterricht(
	u_id int primary key auto_increment,
    klasse varchar(10),
    lehrer varchar(10),
    fach varchar(10),
    raum varchar(10),
    tag int,
    stunde int
)ENGINE=InnoDB 
DEFAULT CHARSET=utf8_general_ci ;


delete from tb_infotainment_unterricht where 1=1;


LOAD DATA INFILE 'nov_2019.txt' 
INTO TABLE tb_infotainment_unterricht 
FIELDS TERMINATED BY ';'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
(@dummy,klasse,lehrer,fach,raum,tag,stunde,@dummy,@dummy);


drop database infotainment_system;

