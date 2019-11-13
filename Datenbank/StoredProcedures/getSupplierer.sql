delimiter //
create procedure sp_getSupplierer(istunde int, itag int, iwoche int)
begin 
	-- select *, abs(a1.stunde-istunde)
    select *,a1.lehrer
	from tb_infotainment_unterricht a1
	left join (
		select t1.lehrer
		from  tb_infotainment_unterricht t1
		left join (
		select lehrer
		from tb_infotainment_unterricht
		where stunde = istunde and tag=itag and fach <> 'SU' and fach <> ''
		group by lehrer
		) t2
		on t1.lehrer = t2.lehrer
		where t1.fach <> 'SU' and t1.fach <> '' and t2.lehrer is null and t1.tag=itag
		group by t1.lehrer
	) a2
	on a1.lehrer = a2.lehrer
	left join(
		select b2.supplierer
		from tb_infotainment_unterricht b1
		left join tb_infotainment_supplieren b2
		on b1.u_id = b2.u_id and woche = iwoche
		where tag = itag and b2.supplierer is not null and stunde = istunde
	) a3
	on a1.lehrer = a3.supplierer
	left join (
		SELECT c1.lehrer
		FROM tb_infotainment_unterricht c1
		left join (
		select u_id
		from tb_infotainment_unterricht
		where tag = itag
		group by lehrer
		) c2
		on c1.u_id = c2.u_id
		left join tb_infotainment_fehlendeLehrer c3
		on c1.u_id = c3.u_id and woche = iwoche
		where c1.tag = itag and c1.lehrer <> '' and c2.u_id is not null and c3.u_id is not null
	) a4
	on a1.lehrer = a4.lehrer
    left join (
		SELECT d1.lehrer as lehrer
		from tb_infotainment_unterricht d1
		left join tb_infotainment_supplieren d2
		on d1.lehrer = d2.supplierer and d2.woche = iwoche
		where d1.fach = 'SU' and d1.tag =itag and stunde=istunde and d2.supplierer is null
    ) a5
    on a1.lehrer = a5.lehrer
	where a1.tag= itag and a2.lehrer is not null and a3.supplierer is null and a4.lehrer is null and a1.fach <> 'SU'
	group by a1.lehrer
    order by abs(a1.stunde-istunde) asc;
end //

delimiter ;
drop procedure sp_getSupplierer;
call sp_getSupplierer(1,3,201946);