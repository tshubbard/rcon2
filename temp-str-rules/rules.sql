INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, command_timer, is_indefinite, is_public, is_active) VALUES ('General Server Broadcast', 1, 0, 'timer', '[{"key": "say", "order": 1, "command": "say Note that this is a limited PVP server. Killing is allowed only when raiding, counter-raiding, in radtowns (anything with a label on the map), and under airdrops (until the drop is looted). For the full list of rules visit: startingtorust.com"}]', 15, 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, command_timer, is_indefinite, is_public, is_active) VALUES ('Next Wipe Broadcast', 1, 0, 'timer', '[{"key": "say", "order": 1, "command": "say Next Full Wipe: June 21st"}]', 30, 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Join Message', 1, 0, 'player.connected', '[{"key": "say", "order": 1, "command": "say ${user.username} has entered the game."}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Kill Message', 1, 0, 'player.killed', '[{"key": "say", "order": 1, "command": "say ${victim.username} was killed by ${killer.username}"}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Supply Drop Message', 1, 0, 'cargo_plane', '[{"key": "say", "order": 1, "command": "say Supply drop incoming."}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Patrol Helicopter Message', 1, 0, 'patrolhelicopter', '[{"key": "say", "order": 1, "command": "say Helicopter incoming."}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Christmas Gifts Message', 1, 0, 'xmasrefill', '[{"key": "say", "order": 1, "command": "say Now I have a machine gun, HO HO HO!"}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Chinook Message', 1, 0, 'ch47scientists.entity', '[{"key": "say", "order": 1, "command": "say Chinook incoming."}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('Cargo Ship Message', 1, 0, 'cargoshiptest', '[{"key": "say", "order": 1, "command": "say Cargo Ship incoming."}]', 1, 0, 1);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, command_timer, is_indefinite, is_public, is_active) VALUES ('PURGE - General Server Broadcast', 1, 0, 'timer', '[{"key": "say", "order": 1, "command": "say PURGE is live!"}]', 15, 1, 0, 0);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, command_timer, is_indefinite, is_public, is_active) VALUES ('PURGE - Weapon Timed Give', 1, 0, 'timer', '[{"key": "giveall", "order": 1, "command": "giveall rifle.bolt"},{"key": "giveall", "order": 2, "command": "giveall weapon.mod.small.scope"},{"key": "giveall", "order": 3, "command": "giveall ammo.rifle.hv 200"}]', 20, 1, 0, 0);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, command_timer, is_indefinite, is_public, is_active) VALUES ('PURGE - Food Timed Give', 1, 0, 'timer', '[{"key": "giveall", "order": 1, "command": "giveall black.raspberries 20"}]', 10, 1, 0, 0);

INSERT INTO server_events (name, server_id, created_by_user_id, event_type, commands, is_indefinite, is_public, is_active) VALUES ('PURGE - On-Spawn Give', 1, 0, 'player.spawned', '[{"key": "giveto", "order": 1, "command": "giveto ${user.steam_id} rocket.launcher"},{"key": "giveto", "order": 2, "command": "giveto ${user.steam_id} ammo.rocket.basic 20"},{"key": "giveto", "order": 2, "command": "giveto ${user.steam_id} ammo.rocket.basic 20"},{"key": "giveto", "order": 3, "command": "giveto ${user.steam_id} pumpkin 3"},{"key": "giveto", "order": 4, "command": "giveto ${user.steam_id} rifle.ak"},{"key": "giveto", "order": 5, "command": "giveto ${user.steam_id} ammo.rifle 200"},{"key": "giveto", "order": 6, "command": "giveto ${user.steam_id} explosive.timed 10"}]', 1, 0, 0);
