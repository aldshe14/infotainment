delimiter //
CREATE TRIGGER checkTimetable
    BEFORE INSERT
    ON tb_infotainment_timetable FOR EACH ROW
begin
	Select * 
    From tb_infotainment_timetable;
end //

delimiter ;

drop trigger checkTimetable;