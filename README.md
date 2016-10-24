Jax - just another comments
===========================

Порядок запуска:

1. Создать автолоадер и загрузить вендоров - php composer.phar install 
2. Создать базу
3. Создать таблицу согласно содержимому base.sql
4. Создать файл config.php в папке src/config/ по образу и подобию src/config/config.php.dist заменив доступы к базе на свои
5. Запустить сервер: Пример - cd web/ && php -S localhost:9020
