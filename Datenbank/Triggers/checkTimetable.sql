delimiter //
CREATE TRIGGER checkTimetable
    BEFORE INSERT
    ON tb_infotainment_timetable FOR EACH ROW
begin
	declare dofw int;
    declare l_von time;
    declare l_bis time;
    declare anz int;
    set anz = 0;
    
    select von into l_von
    from tb_infotainment_timetable_layout
    where t_id = new.tl_id;
	
    select bis into l_bis
    from tb_infotainment_timetable_layout
    where t_id = new.tl_id;
    
	select dayofweek into dofw
    from tb_infotainment_timetable_layout
    where t_id = new.tl_id;
    
	if (dofw & new.dayofweek) > 0 and (new.von<l_von or new.bis>l_bis or new.bis<new.von) then
		signal sqlstate '45000' set message_text = 'My Error Message';
	end if;
    
    
	Select count(*) into anz
    From tb_infotainment_timetable 
    where tl_id = new.tl_id and lsection_id = new.lsection_id and ((new.von <= von and new.bis <= bis and new.bis>=von)
    or (new.von >= von and new.bis <= bis and new.von<=bis and new.bis>=von)
    or (new.von >= von and new.bis <= bis and new.von<=bis) ) and (dayofweek & new.dayofweek) > 0;
    if anz > 0 then
		signal sqlstate '45000' set message_text = 'My Error Message';
	end if;

end //

delimiter ;

drop trigger checkTimetable;