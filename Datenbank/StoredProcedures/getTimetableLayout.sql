delimiter //
create procedure sp_getTimetableLayout(i_id int, out min time, out max time)
begin 
	SELECT * 
    FROM tb_infotainment_timetable_layout
    WHERE display_id = i_id;
    
    SELECT von into min
    FROM tb_infotainment_timetable_layout
    WHERE display_id = i_id
    ORDER BY von asc
    limit 1;
    
	SELECT bis into max
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


