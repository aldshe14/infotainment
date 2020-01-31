create table tb_infotainment_timetable_layout(
	t_id int primary key auto_increment,
    display_id int not null,
    layout_id int not null,
    von time not null,
    bis time not null,
    dayofweek int not null,
    constraint fk_timetable_layout
    foreign key(layout_id)
    references tb_infotainment_layout(l_id),
    constraint fk_timetable_display
    foreign key(display_id)
    references tb_infotainment_display(d_id)
);