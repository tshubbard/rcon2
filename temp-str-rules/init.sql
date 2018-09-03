INSERT INTO users (name, slug, email, password, verified) VALUES ('Lord Amish', 'lord-amish', 'lordamish@startingtorust.com', '$2y$10$bLQQLrN0KHwPwosaM47kpefIbExq4Yuj6a/egfUIfvEmWAAA4ordu', 1);

INSERT INTO users (name, slug, email, password, verified) VALUES ('Haelix', 'haelix', 'haelix@startingtorust.com', '$2y$10$ogE2QYEqkEO9eeqD01J0EuLYdQFMdSs9XOkXUa3vL9OXuB6qu39pO', 1);

INSERT INTO users (name, slug, email, password, verified) VALUES ('DivineRight', 'divineright', 'divineright@startingtorust.com', '$2y$10$14vb0O5xlGeocwosmNW5S.AweKf32kxZzbXYCriBcolgLI3V3fvG2', 1);

INSERT INTO users (name, slug, email, password, verified) VALUES ('ArchPau', 'archpau', 'archpau@startingtorust.com', '$2y$10$qnEP6nAFpqYTVFZ8CPIm1OONMtaJEYuxdpn.mOMNQNnVFNIPDN8Fe', 1);

INSERT INTO users (name, slug, email, password, verified) VALUES ('YoJesse955', 'yojesse955', 'yojesse955@startingtorust.com', '$2y$10$OM1zl16QYCusZIvknHv84.KblhVB6.VtoZPUNQqSmj12q57PwigE.', 1);

INSERT INTO account_user (user_id, account_id) VALUES (1, 1);

INSERT INTO account_user (user_id, account_id) VALUES (2, 1);

INSERT INTO account_user (user_id, account_id) VALUES (3, 1);

INSERT INTO account_user (user_id, account_id) VALUES (4, 1);

INSERT INTO account_user (user_id, account_id) VALUES (5, 1);

INSERT INTO accounts (name, slug, owner_id, description) VALUES ('Starting to Rust', 'starting-to-rust', 1, 'Starting to Rust');

INSERT INTO servers (account_id, name, slug, host, password, port, timezone, is_active) VALUES (1, 'Starting to Rust', 'starting-to-rust', '64.38.249.114', 'Zk8CajTyU2', '28017', 'America/New_York', 0);

INSERT INTO servers (account_id, name, slug, host, password, port, timezone, is_active) VALUES (1, 'Rust Playground', 'rust-playground', '198.24.176.186', 'ruvgahux', '28017', 'America/New_York', 0);
