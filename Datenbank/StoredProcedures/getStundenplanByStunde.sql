delimiter //
create procedure sp_getStundenplanByStunde(istunde int)
begin
	select *
    from tb_infotainment_unterricht
    where stunde = istunde
    order by klasse asc;
end //

delimiter ;
drop procedure sp_getStundenplanByStunde;
call sp_getStundenplanByStunde(1);