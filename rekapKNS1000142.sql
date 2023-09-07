START TRANSACTION;

-- mitra yang dipindah KNS1000142 (member_id 298)
-- upline mitra sebelumnya KNS1000070 (member_id 182)
-- upline mitra setelahnya KNS1000139 (member_id 295)
-- jalur sebelumnya 298 -> 182 -> ...
-- jalur setelahnya 298 -> 295 -> 252 -> 250 -> 244 -> 243 -> 188 -> 182 -> ...

-- 1. update upline KNS142
UPDATE kimstella_dbase.sys_network
	SET network_upline_member_id=295,network_upline_leg_position=1,network_upline_network_code='KNS1000139'
	WHERE network_member_id=298;

-- 2. update sponsor KNS142
UPDATE kimstella_dbase.sys_network
	SET network_sponsor_leg_position=4
	WHERE network_member_id=298;

-- 3. update network upline KNS142
UPDATE kimstella_dbase.sys_network_upline
	SET network_upline_arr_data='[{"level":1,"id":295,"pos":1},{"level":2,"id":252,"pos":1},{"level":3,"id":250,"pos":2},{"level":4,"id":244,"pos":1},{"level":5,"id":243,"pos":1},{"level":6,"id":188,"pos":1},{"level":7,"id":182,"pos":4},{"level":8,"id":31,"pos":3},{"level":9,"id":26,"pos":1},{"level":10,"id":15,"pos":2},{"level":11,"id":5,"pos":1},{"level":12,"id":1,"pos":4}]'
	WHERE network_upline_member_id=298;

-- 4. hapus netgrow node KNS142
DELETE FROM kimstella_dbase.sys_netgrow_node
	WHERE netgrow_node_downline_member_id=298;

-- 5. insert netgrow node KNS142
INSERT INTO sys_netgrow_node
(netgrow_node_id, netgrow_node_member_id, netgrow_node_downline_member_id, netgrow_node_downline_leg_position, netgrow_node_point, netgrow_node_level, netgrow_node_date, netgrow_node_datetime)
VALUES
(NULL, 295, 298, 1, 0.000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 252, 298, 1, 0.000, 2, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 250, 298, 2, 0.000, 3, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 244, 298, 1, 0.000, 4, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 243, 298, 1, 0.000, 5, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 188, 298, 1, 0.000, 6, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 182, 298, 4, 0.000, 7, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 31, 298, 3, 0.000, 8, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 26, 298, 1, 0.000, 9, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 15, 298, 2, 0.000, 10, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 5, 298, 1, 0.000, 11, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 1, 298, 4, 0.000, 12, '2023-02-26', '2023-02-26 12:54:01.000');

-- 6. hapus netgrow gen node KNS142
DELETE FROM kimstella_dbase.sys_netgrow_gen_node
	WHERE netgrow_gen_node_trigger_member_id=298;

-- 7. insert netgrow gen node KNS142
INSERT INTO sys_netgrow_gen_node
(netgrow_gen_node_id, netgrow_gen_node_member_id, netgrow_gen_node_line_member_id, netgrow_gen_node_trigger_member_id, netgrow_gen_node_level, netgrow_gen_node_bonus, netgrow_gen_node_point, netgrow_gen_node_date, netgrow_gen_node_datetime)
VALUES
(NULL, 295, 298, 298, 1, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 252, 298, 298, 2, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 250, 298, 298, 3, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 244, 298, 298, 4, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 243, 298, 298, 5, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 188, 298, 298, 6, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 182, 298, 298, 7, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 31, 298, 298, 8, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000'),
(NULL, 26, 298, 298, 9, 10000, 1, '2023-02-26', '2023-02-26 12:54:01.000');

-- 8. hapus netgrow power leg KNS142
DELETE FROM kimstella_dbase.sys_netgrow_power_leg
	WHERE netgrow_power_leg_trigger_member_id=298;

-- 9. insert netgrow matching leg KNS142
INSERT INTO sys_netgrow_power_leg
(netgrow_power_leg_id, netgrow_power_leg_member_id, netgrow_power_leg_line_member_id, netgrow_power_leg_line_leg_position, netgrow_power_leg_trigger_member_id, netgrow_power_leg_bonus, netgrow_power_leg_point, netgrow_power_leg_date, netgrow_power_leg_datetime)
VALUES(NULL, 250, 298, 8, 298, 40000, 2, '2023-02-26', '2023-02-26 12:54:01.000');

COMMIT;