delimiter //
create procedure sp_getTimetableLayout(i_id int, out min time, out max time)
begin 
	SELECT * 
    FROM tb_infotainment_timetable_layout
    WHERE display_id = i_id;
    
    SELECT DATE_ADD(DATE_ADD(von,INTERVAL (IF(SECOND(von) < 30, 0, 60) - SECOND(von)) SECOND),INTERVAL (IF(MINUTE(von) <= 60, 0, 60) - MINUTE(von)) MINUTE) into min
    FROM tb_infotainment_timetable_layout
    WHERE display_id = i_id
    ORDER BY von asc
    limit 1;
    
	SELECT DATE_ADD(DATE_ADD(bis,INTERVAL (IF(SECOND(bis) < 30, 0, 60) - SECOND(bis)) SECOND),INTERVAL (IF(MINUTE(bis) <= 60, 0, 60) - MINUTE(bis)) MINUTE) into max
    FROM tb_infotainment_timetable_layout
    WHERE display_id = i_id
    ORDER BY bis desc
    limit 1;
end //
delimiter ;

drop procedure sp_getTimetableLayout;
call sp_getTimetableLayout(55,@min,@max);

select @min as min, @max as max;     

SELECT * 
FROM tb_infotainment_timetable_layout;


