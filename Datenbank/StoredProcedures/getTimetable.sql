delimiter //
create procedure sp_getTimetable(i_id int)
begin 
	SELECT *
    FROM tb_infotainment_timetable
    WHERE tl_id = i_id
    order by von;
    
end //
delimiter ;

drop procedure sp_getTimetable;
call sp_getTimetable(38);

SELECT * 
FROM tb_infotainment_timetable;


