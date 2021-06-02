### How to start the project (Docker / Docker-compose)
This project is dockerized .

- Install docker and docker compose 
- run `docker-compose build` 
- run `docker-compose exec -it notification_Adastra_one-mysql bash` 
- run `mysql -u root -p password`
- run `create database notification_Adastra_one charset utf8;`
- run `docker-compose exec -it notification_Adastra_one bash`
- run `php artisan migrate`
- run `php artisan db:seed`

### How to start the project without docker 
- Install php ideal V7.4 ,mysql (any ver.) and composer (any ver.)
- run `composer i`
- run `cp .env.example .env` 
- change .env file to suite your database 
- run `create database notification_Adastra_one charset utf8;`
- run `php artisan migrate`
- run `php artisan db:seed`

# Good Luck <3 ;)
