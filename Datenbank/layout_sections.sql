create table tb_infotainment_layout_sections(
	l_id int primary key auto_increment,
    name varchar(20) not null,
    layout_id int not null,
	constraint fk_layout_sections_layout
    foreign key(layout_id)
    references tb_infotainment_layout(l_id)
);