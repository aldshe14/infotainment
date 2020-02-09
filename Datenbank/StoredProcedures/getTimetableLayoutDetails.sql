delimiter //
create procedure sp_getTimetableLayoutDetails(i_id int)
begin 
	SELECT tl.t_id as t_id, d.d_id as d_id,tl.von as von, tl.bis as bis, tl.dayofweek as dayofweek, d.name as 'displayname',
    l.beschreibung as 'layoutname', ls.name as 'layoutsection'
    FROM tb_infotainment_timetable_layout tl
    join tb_infotainment_layout l
    on tl.layout_id = l.l_id
    join tb_infotainment_layout_sections ls
    on tl.layout_id = ls.layout_id
    join tb_infotainment_display d
    on tl.display_id = d.d_id
    WHERE t_id = i_id
    order by tl.von;
    
end //
delimiter ;

drop procedure sp_getTimetableLayoutDetails;
call sp_getTimetableLayoutDetails(34);

select @min as min, @max as max;     

SELECT * 
FROM tb_infotainment_timetable_layout;


