create table tb_infotainment_timetable(
	t_id int primary key auto_increment,
    tl_id int not null,
    name varchar(50) not null,
    a_id int not null,
    lsection_id int not null,
    von time not null,
    bis time not null,
    dayofweek int not null,
    constraint fk_timetablelayout_timetable
    foreign key(tl_id)
    references tb_infotainment_timetable_layout(t_id),
    constraint fk_timetable_layout_section
    foreign key(lsection_id)
    references tb_infotainment_layout_sections(l_id)
);

