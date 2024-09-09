#!/bin/bash

# Set the timezone to EST (Eastern Standard Time)
echo "Setting MySQL timezone to EST"
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    SET GLOBAL time_zone = 'America/New_York';
EOSQL

# Create the database and user
echo "Creating database ${MYSQL_DATABASE} and user ${MYSQL_USER}"
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};
    CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';
    GRANT ALL PRIVILEGES ON ${MYSQL_DATABASE}.* TO '${MYSQL_USER}'@'%';
    FLUSH PRIVILEGES;
EOSQL

echo "Database and user creation complete!"

# Create the posts table
echo "Creating posts table in ${MYSQL_DATABASE}"
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} <<-EOSQL
    CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
EOSQL

# Create users table
echo "Creating users table in ${MYSQL_DATABASE}"
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} <<-EOSQL
    CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
EOSQL

# Create comments table
echo "Creating comments table in ${MYSQL_DATABASE}"
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} <<-EOSQL
    CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
EOSQL

echo "Table creation complete!"
