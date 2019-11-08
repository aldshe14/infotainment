SELECT u.u_id as id, u.unterricht_nr as unterricht_nr, u.klasse as klasse, u.lehrer as lehrer, u.raum as raum, u.fach as fach, u.tag as tag, u.stunde as stunde
FROM tb_infotainment_unterricht u left join tb_infotainment_supplieren s on u.lehrer = s.lehrer and u.stunde = s.stunde
WHERE s.lehrer is null and u.tag ="4" and u.lehrer = 'AIT' and u.fach <> 'SU' order by u.stunde asc;

SELECT *
FROM tb_infotainment_unterricht u left join tb_infotainment_supplieren s on u.lehrer = s.lehrer and u.stunde = s.stunde and u.tag = dayofweek(s.datum)
WHERE s.lehrer is null and u.tag ="4" and u.fach <> 'SU';

SELECT * 
FROM tb_infotainment_unterricht u 
left join tb_infotainment_supplieren s
on u.lehrer = s.supplierer
WHERE u.tag ="5" and u.stunde = "1" and u.fach = 'SU' and s.supplierer is null;

select * 
FROM tb_infotainment_unterricht
where tag="5" and stunde <> "1"
group by lehrer;

