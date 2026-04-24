DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS expense_share;
DROP TABLE IF EXISTS expense;
DROP TABLE IF EXISTS activity;
DROP TABLE IF EXISTS group_invite;
DROP TABLE IF EXISTS group_member;
DROP TABLE IF EXISTS trip;
DROP TABLE IF EXISTS travel_group;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE travel_group (
    group_id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    group_code VARCHAR(50) NOT NULL UNIQUE,
    created_by_user_id INT NOT NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id)
);

CREATE TABLE group_member (
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role VARCHAR(50) NOT NULL,
    joined_at DATETIME NOT NULL,
    PRIMARY KEY (group_id, user_id),
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE trip (
    trip_id INT AUTO_INCREMENT PRIMARY KEY,
    trip_name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    group_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id),
    CHECK (end_date >= start_date)
);

CREATE TABLE expense (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    expense_date DATE NOT NULL,
    description VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    trip_id INT NOT NULL,
    paid_by_user_id INT NOT NULL,
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id),
    FOREIGN KEY (paid_by_user_id) REFERENCES users(user_id),
    CHECK (amount > 0)
);

CREATE TABLE expense_share (
    expense_id INT NOT NULL,
    user_id INT NOT NULL,
    share_amount DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (expense_id, user_id),
    FOREIGN KEY (expense_id) REFERENCES expense(expense_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    CHECK (share_amount > 0)
);

CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    note VARCHAR(255),
    trip_id INT NOT NULL,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id),
    FOREIGN KEY (from_user_id) REFERENCES users(user_id),
    FOREIGN KEY (to_user_id) REFERENCES users(user_id),
    CHECK (amount > 0),
    CHECK (from_user_id <> to_user_id)
);

CREATE TABLE activity (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_type VARCHAR(100) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    activity_date DATE NOT NULL,
    cost DECIMAL(10,2) NOT NULL,
    location VARCHAR(150) NOT NULL,
    trip_id INT NOT NULL,
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id),
    CHECK (cost >= 0),
    CHECK (end_time > start_time)
);

CREATE TABLE group_invite (
    invite_id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    invited_email VARCHAR(150) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL,
    invited_by_user_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id),
    FOREIGN KEY (invited_by_user_id) REFERENCES users(user_id)
);

DELIMITER $$

CREATE TRIGGER check_share_amount
BEFORE INSERT ON expense_share
FOR EACH ROW
BEGIN
    DECLARE total_amount DECIMAL(10,2);
    DECLARE current_total DECIMAL(10,2);

    SELECT amount
    INTO total_amount
    FROM expense
    WHERE expense_id = NEW.expense_id;

    SELECT COALESCE(SUM(share_amount), 0)
    INTO current_total
    FROM expense_share
    WHERE expense_id = NEW.expense_id;

    IF current_total + NEW.share_amount > total_amount THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Total share_amount cannot be greater than expense amount';
    END IF;
END$$

DELIMITER ;

INSERT INTO users (name, email, password) VALUES
('Carlos Orellana', 'carlos@example.com', 'password1'),
('Santiago Escobar', 'santiago@example.com', 'password2'),
('Brayan Chavez', 'brayan@example.com', 'password3'),
('Steven Zheng', 'steven@example.com', 'password4'),
('Guest Member', 'guest@example.com', 'password5');

INSERT INTO travel_group (group_name, description, group_code, created_by_user_id) VALUES
('Spring Break Trip', 'Friends planning a shared spring break trip.', 'SPRING2026', 1),
('NYC Weekend', 'A quick weekend group trip to New York City.', 'NYC2026', 2);

INSERT INTO group_member (group_id, user_id, role, joined_at) VALUES
(1, 1, 'owner', '2026-02-01 10:00:00'),
(1, 2, 'member', '2026-02-01 10:05:00'),
(1, 3, 'member', '2026-02-01 10:10:00'),
(1, 4, 'member', '2026-02-01 10:15:00'),
(2, 2, 'owner', '2026-02-05 10:00:00'),
(2, 5, 'member', '2026-02-05 10:10:00');

INSERT INTO trip (trip_name, start_date, end_date, group_id) VALUES
('Miami Beach', '2026-03-20', '2026-03-25', 1),
('NYC Weekend Trip', '2026-04-10', '2026-04-12', 2);

INSERT INTO expense (amount, expense_date, description, category, trip_id, paid_by_user_id) VALUES
(200.00, '2026-03-20', 'Hotel Booking', 'Lodging', 1, 1),
(80.00, '2026-03-21', 'Dinner', 'Food', 1, 2),
(120.00, '2026-04-10', 'Broadway Tickets', 'Entertainment', 2, 2);

INSERT INTO expense_share (expense_id, user_id, share_amount) VALUES
(1, 1, 50.00),
(1, 2, 50.00),
(1, 3, 50.00),
(1, 4, 50.00),
(2, 1, 20.00),
(2, 2, 20.00),
(2, 3, 20.00),
(2, 4, 20.00);

INSERT INTO payment (amount, payment_date, note, trip_id, from_user_id, to_user_id) VALUES
(50.00, '2026-03-22', 'Hotel share', 1, 2, 1),
(20.00, '2026-03-22', 'Dinner split', 1, 1, 2);

INSERT INTO activity (activity_type, start_time, end_time, activity_date, cost, location, trip_id) VALUES
('Beach Day', '09:00:00', '13:00:00', '2026-03-21', 0.00, 'South Beach', 1),
('Museum Visit', '10:00:00', '12:00:00', '2026-04-11', 25.00, 'MoMA', 2);

INSERT INTO group_invite (group_id, invited_email, status, created_at, invited_by_user_id) VALUES
(1, 'friend@example.com', 'pending', '2026-02-02 12:00:00', 1),
(2, 'guest@example.com', 'pending', '2026-02-06 10:00:00', 2);
