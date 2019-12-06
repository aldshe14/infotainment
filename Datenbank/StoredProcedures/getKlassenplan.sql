delimiter //
create procedure sp_getKlassenplan(itag int, iklasse varchar(3))
begin
	select *
    from tb_infotainment_unterricht
    where tag = itag and klasse like iklasse;
end //

delimiter ;
drop procedure sp_getKlassenplan;
call sp_getKlassenplan(1,"9a");