delimiter //
CREATE TRIGGER checkTimetableLayout
    BEFORE INSERT
    ON tb_infotainment_timetable_layout FOR EACH ROW
begin
	declare anz int;
    set anz = 0;
    
	Select count(*) into anz
    From tb_infotainment_timetable_layout 
    where display_id = new.display_id and ((new.von <= von and new.bis <= bis and new.bis>=von)
    or (new.von >= von and new.bis <= bis and new.von<=bis and new.bis>=von)
    or (new.von >= von and new.bis <= bis and new.von<=von) ) and (dayofweek & new.dayofweek) > 0 or (new.von > new.bis);
    if anz > 0 then
		signal sqlstate '45000' set message_text = 'My Error Message';
	end if;
end //

delimiter ;

drop trigger checkTimetableLayout;