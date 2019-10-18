create table tb_infotainment_weather(
	w_id int primary key auto_increment,
    datum date not null,
    zeit time not null,
    beschreibung varchar(250) not null,
    temp double not null,
    temp_min double not null,
    temp_max double not null,
    humidity int(3) not null,
    sunrise time not null,
    sunset time not null,
    wind_speed double not null,
    wind_deg int not null,
    icon varchar(3) not null,
    city_name varchar(50) not null
);