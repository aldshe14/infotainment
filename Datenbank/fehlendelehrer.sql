create table tb_infotainment_fehlendeLehrer(
	u_id int,
    woche int,
    primary key(u_id,woche),
    constraint fk_fehlendeLehrer_unterricht
    foreign key(u_id) 
    references tb_infotainment_unterricht(u_id)
);



SELECT *
FROM tb_infotainment_unterricht u
left join (
	select u_id
    from tb_infotainment_unterricht
    where tag = 1
    group by lehrer
) u1
on u.u_id = u1.u_id
left join tb_infotainment_fehlendeLehrer l
on u.u_id = l.u_id and woche = 201946
where u.tag = 1 and u.lehrer <> ''and u1.u_id is not null and l.u_id is not null
order by u.klasse asc ;

SELECT u.lehrer as lehrer
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer and woche = 201946
where u.fach = 'SU' and tag =1 and stunde=1 and s.supplierer is null;

SELECT *,u.u_id as id,u.lehrer as lehrer
from tb_infotainment_unterricht u
left join tb_infotainment_fehlendelehrer f
on u.u_id = f.u_id and f.woche = 201946
left join (select lehrer
from tb_infotainment_unterricht tu
where tu.stunde = 1 and tu.tag=1 
group by lehrer) u1
on u.lehrer = u1.lehrer
left join (
Select a.lehrer
from tb_infotainment_unterricht a
left join tb_infotainment_supplieren s
on a.u_id = s.u_id
where a.tag=1
group by a.lehrer
 ) s
on u.lehrer = s.lehrer and woche = 201946
where u.tag=1  and u.fach <> 'SU' and u.lehrer <> ''
group by u.lehrer
having s.lehrer is null;


------------
