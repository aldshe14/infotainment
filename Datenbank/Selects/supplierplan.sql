
-- Select oret qe ka nje msus pa ato qe jane te zevendesume dhe ekzistojne te supplieren
select * 
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.u_id = s.u_id
where fach <> 'SU' and tag = 1 and supplierer is null and lehrer = "AIT"
order by stunde asc;

SELECT *,u.u_id as u_id
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.u_id = s.u_id
where u.fach <> 'SU' and u.tag = 5 and u.lehrer = "AIT" and timestampdiff(day,s.datum,DATE(now()))
order by u.stunde asc;

 and timestampdiff(day,s.datum,now());

-- Select msusat qe munden me zevendesu 1 ore te caktume
SELECT *
from tb_infotainment_unterricht u
left join tb_infotainment_supp_unter z
on u.u_id = z.u_id
left join tb_infotainment_supplieren s
on z.s_id = s.s_id
WHERE u.tag =5 and u.stunde = 1;

select u.lehrer as lehrer
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer
where u.fach = 'SU' and tag = 5 and stunde=1 and s.supplierer is null;

SELECT *
from tb_infotainment_unterricht u
left join (select lehrer
from tb_infotainment_unterricht tu
where tu.stunde = 1 and tu.tag=5 ) u1
on u.lehrer = u1.lehrer
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer
where u1.lehrer is null and u.fach <> 'SU' and u.tag = 5 and u.lehrer <> '' and s.supplierer is null
group by u.lehrer;

-- Selekt supplierlehrer fuer eine stunde :) working
select *
from tb_infotainment_unterricht u
left join (select lehrer
from tb_infotainment_unterricht tu
where tu.stunde = 1 and tu.tag=1 ) u1
on u.lehrer = u1.lehrer
left join tb_infotainment_supp_unter z
on z.u_id = u.u_id
where u.tag=1 and z.u_id is null and u.fach <> 'SU' and u.lehrer <> '' and u1.lehrer is null
group by u.lehrer;
---------------------------------------------------

select * 
FROM tb_infotainment_unterricht
where ((stunde <> "1" and tag ="5") )and lehrer <> ''   
group by lehrer;

select lehrer
from tb_infotainment_unterricht
where stunde = 1 and tag=5
group by lehrer;

select * 
from tb_infotainment_unterricht u
left join (select lehrer
from tb_infotainment_unterricht
where stunde = 1 and tag=1) u1
on u.lehrer = u1.lehrer
left join tb_infotainment_supp_unter z
on u.u_id = z.u_id
where u1.lehrer is null and u.fach <> 'SU' and tag = 1
group by u.lehrer;

SELECT *, u.u_id as u_id
from tb_infotainment_unterricht u
left join tb_infotainment_supp_unter z
on u.u_id = z.u_id
left join tb_infotainment_supplieren s
on z.s_id = s.s_id
where u.fach <> 'SU' and u.tag = 5 and supplierer is null and u.lehrer = "AIT" or woche <> 201945
having u.tag = 5
order by u.stunde asc;

select *
from tb_infotainment_unterricht
where tag=5 and lehrer = "AIT";


------------------------------------------------------
-- msusi nuk mungon
select * 
from tb_infotainment_unterricht e
left join(
select u.lehrer
from tb_infotainment_unterricht u
join tb_infotainment_supp_unter z
on u.u_id = z.u_id
where tag=1 and z.u_id is not null
group by u.lehrer) f
on e.lehrer = f.lehrer
where e.tag = 1 and e.fach <> '' and f.lehrer is null 
group by e.lehrer
;

-- msusi nuk ka msimi ate ore
select *
from  tb_infotainment_unterricht u
left join (
select lehrer
from tb_infotainment_unterricht
where stunde = 1 and tag=1
) u1
on u.lehrer = u1.lehrer
where u.fach <> 'SU' and u.fach <> '' and u1.lehrer is null
group by u.lehrer;

-- msusi nuk ka zevendesim ate ore

select * 
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer
where u.fach <> 'SU' and u.fach <> '' and s.supplierer is null
group by u.lehrer;



-- Try all together
select * from tb_infotainment_unterricht a
left join
(select u.lehrer
from tb_infotainment_unterricht u
left join tb_infotainment_supp_unter z
on u.u_id = z.u_id
where tag=1 and z.u_id is null
group by u.lehrer) b
on a.lehrer = b.lehrer
left join 
(select u.lehrer
from  tb_infotainment_unterricht u
left join (
select lehrer
from tb_infotainment_unterricht
where stunde = 1 and tag=1
) u1
on u.lehrer = u1.lehrer
where u.fach <> 'SU' and u.fach <> '' and u1.lehrer is null
group by u.lehrer) c
on a.lehrer = c.lehrer
left join 
(select u.lehrer
from tb_infotainment_unterricht u
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer
where u.fach <> 'SU' and u.fach <> '' and s.supplierer is null
group by u.lehrer) d
on a.lehrer = d.lehrer
where a.fach <> 'SU' and a.fach <> ''
group by a.lehrer
having b.lehrer is not null and c.lehrer is not null and d.lehrer is not null;