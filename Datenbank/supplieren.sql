create table tb_infotainment_supplieren(
	s_id int primary key auto_increment,
    supplierer varchar(3) not null,
    beschreibung varchar(50)
);

create table tb_infotainment_supp_unter(
	u_id int not null,
    s_id int not null,
    woche int not null default YEARWEEK(date(now())),
    primary key(u_id,s_id,woche),
    constraint fk_supp_unterricht
    foreign key(u_id)
    references tb_infotainment_unterricht(u_id),
    constraint fk_unterricht_supp
    foreign key(s_id)
    references tb_infotainment_supplieren(s_id)
);

SELECT *,u.u_id as u_id, u.stunde as stunde
            from tb_infotainment_unterricht u
            left join tb_infotainment_supp_unter z
            on u.u_id = z.u_id
             left join tb_infotainment_supplieren s
            on z.s_id = s.s_id;
insert tb_infotainment_supplieren(supplierer,datum,beschreibung)values("AIT","2019-01-01","");
select last_insert_id();
drop table tb_infotainment_supp_unter;