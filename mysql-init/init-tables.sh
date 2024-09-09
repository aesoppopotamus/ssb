#!/bin/bash

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

echo "Table creation complete!"
