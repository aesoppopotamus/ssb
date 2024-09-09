#!/bin/bash

# Step 1: Stop and remove all containers defined in docker-compose.yml
echo "Stopping and removing containers..."
docker-compose down

# Step 2: Remove any orphaned volumes (optional, if not needed anymore)
echo "Removing orphaned volumes..."
docker volume prune -f

# Step 3: Remove unused images
echo "Cleaning up dangling images..."
docker image prune -f

# Step 4: Rebuild and start the containers in detached mode
echo "Rebuilding and starting containers..."
docker-compose up --build -d

# Step 5: Show logs (optional: you can focus on php logs or any other service)
echo "Showing logs for PHP service..."
docker-compose logs -f php
