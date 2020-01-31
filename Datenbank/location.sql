create table tb_infotainment_location(
	l_id int primary key auto_increment,
	name varchar(50) not null
);

INSERT INTO `infotainment_system`.`tb_infotainment_location`(`name`) VALUES ("-");


SELECT d_id
    FROM tb_infotainment_display d
    join tb_infotainment_layout l
    on d.layout_id = l.l_id
    where mac = "the" and l.name like '-';