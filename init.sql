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
    group_code VARCHAR(50) NOT NULL UNIQUE,
    created_by_user_id INT NOT NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE group_member (
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role VARCHAR(50) NOT NULL,
    joined_at DATETIME NOT NULL,
    PRIMARY KEY (group_id, user_id),
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE trip (
    trip_id INT AUTO_INCREMENT PRIMARY KEY,
    trip_name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    group_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
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
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (paid_by_user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CHECK (amount > 0)
);

CREATE TABLE expense_share (
    expense_id INT NOT NULL,
    user_id INT NOT NULL,
    share_amount DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (expense_id, user_id),
    FOREIGN KEY (expense_id) REFERENCES expense(expense_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CHECK (share_amount >= 0)
);

CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    note VARCHAR(255) NOT NULL,
    trip_id INT NOT NULL,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (from_user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
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
    FOREIGN KEY (trip_id) REFERENCES trip(trip_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CHECK (cost >= 0),
    CHECK (end_time >= start_time)
);

CREATE TABLE group_invite (
    invite_id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    invited_email VARCHAR(150) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL,
    invited_by_user_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES travel_group(group_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (invited_by_user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);