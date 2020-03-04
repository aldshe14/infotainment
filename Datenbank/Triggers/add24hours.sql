DELIMITER //

CREATE TRIGGER add24hours
BEFORE INSERT
ON tb_infotainment_password_reset FOR EACH ROW

BEGIN

SET NEW.expire = CURRENT_TIMESTAMP + INTERVAL 7 HOUR;
SET NEW.id = md5(current_timestamp() + interval 2 hour);

END //
DELIMITER ;