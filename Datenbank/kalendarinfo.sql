create table tb_infotainment_kalendarinfo(
	k_id int primary key, 
	title varchar(100) not null,
    beschreibung varchar(250),
    begin datetime not null,
    end datetime
);

delete from `tb_infotainment_chatbot_users` where c_id = 707072293;
delete from `tb_infotainment_chatbot_users` where 1;
SELECT `tb_infotainment_chatbot_users`.`c_id`,
    `tb_infotainment_chatbot_users`.`user_status`,
    `tb_infotainment_chatbot_users`.`role`,
    `tb_infotainment_chatbot_users`.`telefonnummer`,
    `tb_infotainment_chatbot_users`.`checked`
FROM `infotainment_system`.`tb_infotainment_chatbot_users`;
