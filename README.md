
Requirements
1. Composer - https://getcomposer.org/
2. node package manager - https://nodejs.org/en/


Note: Follow the installation steps in LARAVEL 7 - https://laravel.com/docs/7.x/installation


#Installation Guidelines
1. open CLI/CMD
2. go to drive D
3. run: git clone https://github.com/kevinpauneljohn/realEstate.git
4. run: cd realEstate
5. run: npm install
6. run: composer install
7. copy everything from the .env.example to .env file
8. run: php artisan key:generate
9. open the .env file then place your database name to the DB_DATABASE variable
10. back to the CLI and make sure it's on the root folder of realEstate. 
    run: php artisan migrate --seed
11. run: php artisan set:rank
12. run: php artisan serve
13. make sure your xampp/wamp server is running
14. from your cli, you will see: Laravel development server started: http://127.0.0.1:8000
    access the "http://127.0.0.1:8000" in your browser
15. username: kevinpauneljohn password: 123
 

 
