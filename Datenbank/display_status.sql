create table tb_infotainment_display_status(
	d_id int primary key auto_increment,
    status int(1) not null default 1,
    lastChecked datetime default now(),
    constraint fk_display_display_status
    foreign key(d_id)
    references tb_infotainment_display(d_id)
);

drop table tb_infotainment_display_status;