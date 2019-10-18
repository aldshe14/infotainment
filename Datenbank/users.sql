create table tb_infotainment_users(
	u_id int primary key auto_increment,
    u_email varchar(250) not null,
    u_pswd varchar(250) not null,
    u_nickname varchar(20) not null,
    u_register date not null default now(),
    u_role int not null,
    constraint fk_users_roles
    foreign key (u_role)
    references tb_infotainment_roles(r_id)
);

INSERT INTO `tb_infotainment_users` (`u_id`, `u_email`, `u_pswd`, `u_nickname`, `u_register`, `u_role`) VALUES (NULL, 'aldshe14@htl-shkoder.com', '$2y$10$JJbvQi9tniSEAVDG1U9Q2uywEQm4Jg04QjxW6wZPcYdhzalbBobeG', 'Aldo', current_timestamp(), '777');
INSERT INTO `tb_infotainment_users` (`u_id`, `u_email`, `u_pswd`, `u_nickname`, `u_register`, `u_role`) VALUES (NULL, 'irebal14@htl-shkoder.com', '$2y$10$JJbvQi9tniSEAVDG1U9Q2uywEQm4Jg04QjxW6wZPcYdhzalbBobeG', 'Irena', current_timestamp(), '777');

