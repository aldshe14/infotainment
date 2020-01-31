create table tb_infotainment_display(
	d_id int primary key auto_increment,
    mac varchar(17) not null,
    ip varchar(15),
    name varchar(50) not null,
    location_id int not null,
    layout_id int not null,
    constraint fk_display_layout
    foreign key(layout_id)
    references tb_infotainment_layout(l_id),
    constraint fk_display_location
    foreign key(location_id)
    references tb_infotainment_location(l_id)
);

drop table tb_infotainment_display;