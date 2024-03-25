## Lara_REST_API - Laravel Image Manipulation REST API
(See full project creation steps in steps.txt)  
(Tutorial follow-up of https://youtu.be/bvvVX9Pny84 / https://github.com/thecodeholic/laravel-image-manipulation-rest-api ,  
recreated from scratch with Laravel 11)

## Prerequisites
-php 8.3 (with php8.3-mbstring php8.3-intl php8.3-curl php8.3-mysql php8.3-gd)  
-mysql 8.0 (might work on older versions as well)

### Installation steps (local env)
1. git clone the project
2. create `lara_rest_api` database. you can use whatever you want, e.g. phpmyadmin, mysql cli, etc. to use mysql cli:  
    -enter mysql cli by running "mysql -u root" (or whatever mysql user you use instead of root)  
    -make sure your mysql's default storage engine is InnoDB. e.g. run "SHOW ENGINES;" (for InnoDB you should have DEFAULT in the Suport column)  
    -run "CREATE DATABASE lara_rest_api CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"  
3. go to the project root directory using command line
4. run `composer install`
5. copy `env.example` into a new `.env` file 
6. open `.env` and adjust the `DB_*` parameters (make sure `DB_USERNAME` and `DB_PASSWORD` correspond to your mysql user & password. All the 
other `DB_` params are already set)
7. run `php artisan key:generate --ansi`
8. run `php artisan migrate`
9. run `npm install` then a) run `npm run dev` and let it run, OR b) run `npm run build`
10. Run `php artisan serve` which will start the server at http://localhost:8000

### Test locally
1. import postman_collection.json in your postman
2. go to http://127.0.0.1:8000/register - create an user. You will be redirected to http://127.0.0.1:8000/dashboard
3. in http://127.0.0.1:8000/dashboard, press the 'CREATE NEW TOKEN' button, give it a name, then press 'GENERATE'
4. copy the value under 'TOKEN' in the postman collection
    -right click on the collection name, click Edit
    -go to Variables, and copy its value in the 'Initial value' and 'Current value' of the 'TOKEN' variable
    -press CTRL+S to save
5. (optional, but do it to prove the point(i.e. that we are using API token authentication):  
log out from the http://127.0.0.1:8000  web page you're in)
6. now you can run the postman collection endpoints. You can do steps 2-5 again to create a new user if you want,
and copy this 2nd user's token in a new variable also called 'TOKEN', and enable/disable the 1st/2nd user's 'TOKEN'
to see how the code runs (i.e. you should only have 1 'TOKEN' variable enabled)