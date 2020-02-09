delimiter //
create procedure sp_getTimetableDetails(i_id int)
begin 
	SELECT t.t_id as t_id, tl.t_id as tl_id, t.lsection_id as section, t.von as von, t.bis as bis, t.dayofweek as dayofweek,
    t.name as name, t.a_id as a_id, ls.name as sectionname
    FROM tb_infotainment_timetable t
    JOIN tb_infotainment_timetable_layout tl
    ON t.tl_id = tl.t_id
    JOIN tb_infotainment_layout_sections ls
    ON t.lsection_id = ls.l_id
    WHERE t.tl_id = i_id
    ORDER BY t.von;
    
end //
delimiter ;

drop procedure sp_getTimetableDetails;
call sp_getTimetableDetails(34);


SELECT * 
FROM tb_infotainment_timetable;


