create view vDisplays
as select d.d_id as id, d.name as name, d.mac as mac, l.name as layout, loc.name as location 
from tb_infotainment_display d
join tb_infotainment_layout l
on d.layout_id = l.l_id
join tb_infotainment_location loc
on d.location_id = loc.l_id;