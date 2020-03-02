delimiter //
create procedure sp_getLayout(imac varchar(17))
begin 
	declare iday int;
    set iday = dayofweek(now());
if iday=3 then
		set iday=4;
	elseif iday=4 then
		set iday=8;
	elseif iday=5 then
		set iday=16;
	elseif iday=6 then
		set iday=32;
	elseif iday=7 then
		set iday=64;
    end if;
	Select tl.t_id as t_id,  timestampdiff(second,now(),tl.bis) as 'reloadtime', l.file as layout
    from tb_infotainment_timetable_layout tl
    join tb_infotainment_display d
    on tl.display_id = d.d_id
    join tb_infotainment_layout l
    on tl.layout_id = l.l_id
    where d.mac = imac and timestampdiff(minute,now(),tl.von)<=0 and timestampdiff(minute,now(),tl.bis)>=0 and (tl.dayofweek & iday) >0;
end //
delimiter ;

drop procedure sp_getLayout;
call sp_getLayout("b8:27:eb:c1:e6:4e");

select dayofweek(now()) from tb_infotainment_timetable_layout where (dayofweek(now()) & 8) >  0;



