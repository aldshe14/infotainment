delimiter //
CREATE TRIGGER checkLehrer
    BEFORE DELETE
    ON tb_infotainment_fehlendelehrer FOR EACH ROW
begin
	declare status int;
    declare ilehrer varchar(3);
    declare itag int;
    select u.lehrer into ilehrer
    from tb_infotainment_unterricht u
    join tb_infotainment_fehlendeLehrer f
    on u.u_id = f.u_id and f.woche = old.woche
    where u.u_id = old.u_id;
    select u.tag into itag
    from tb_infotainment_unterricht u
    join tb_infotainment_fehlendeLehrer f
    on u.u_id = f.u_id and f.woche = old.woche
    where u.u_id = old.u_id;
	select count(*) into status
	from tb_infotainment_unterricht a
	left join(
		select u.lehrer
		from tb_infotainment_unterricht u
		join tb_infotainment_fehlendelehrer f
		on u.u_id = f.u_id and f.woche = old.woche
		where u.lehrer = ilehrer and u.tag= itag
	) a1
	on a.lehrer = a1.lehrer 
	left join tb_infotainment_supplieren s
	on a.u_id = s.u_id
	where a1.lehrer is not null and tag= itag and s.u_id is not null;
    if status>0 
    then 
		signal sqlstate '45000' set message_text = 'Lehrer kann nicht geloescht werden, weil Supplierstunde hat!';
    end if;
end //

delimiter ;

drop trigger checklehrer;

