## Docker

```

// in root of project
cd docker
docker-compose up

// for execute container with php
docker-compose exec app bash

// for execute container with mysql
docker-compose exec db bash

```

## Installation

```

composer install

php artisan key:generate

php artisan migrate --seed

php artisan passport:install

```

## API routes

```

// AUTH
POST api/register - register
    {
      "first_name": "First",
      "last_name": "Last",
      "email": "example@gmail.com",
      "password": "password"
    }

POST api/login - login
    {
      "email": "example@gmail.com",
      "password": "password"
    }

// USER
GET api/user - get all users

GET api/user/{id}  - show user's info

DELETE api/user/{id} - delete user

PUT api/user/{id} - edit user's info
    {
      "first_name": "First",
      "last_name": "Last",
      "email": "example@gmail.com",
    }

// TASKS
GET api/task - get all tasks
    also with sort params: 
         ?sort="status" - sort by status
         ?sort="recent-users" - sort by recent users
         ?sort="latest-user" - sort by latest users

GET api/task/{id} - get task's info

PUT api/task/{id}/change-status - change status in the task
    {
      "status": "view"
    }

PUT api/task/{id}/change-user - change user in the task
    {
      "user_id": 1
    }

DELETE api/task/{id} - delete task

PUT api/task/{id} - edit task
    {
      "title": "some title",
      "description": "some description"
    }

```
