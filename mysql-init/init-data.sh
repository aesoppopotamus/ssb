#!/bin/bash

# Add me as admin
echo "Adding admin user."
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} <<-EOSQL
    INSERT INTO users (username, password) VALUES ('${ADMIN_USERNAME}', PASSWORD('${ADMIN_PASSWORD}'));
);
EOSQL