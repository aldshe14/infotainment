delimiter //
create procedure sp_insertTimetableLayout(did int, lid int, ivon time, ibis time, day int, out id_ret int)
begin 
	Insert into tb_infotainment_timetable_layout(display_id,layout_id,von,bis,dayofweek) VALUES (did,lid,ivon, ibis, day);
    
    SELECT LAST_INSERT_ID() into id_ret;
end //
delimiter ;

drop procedure sp_insertTimetableLayout;
call sp_insertTimetableLayout(55,1,'13:15:13','13:16:13',32,@out);

select @out;

