# commentary-app
Функционал 'комментарии' для тестового задания
# 1. Установка
Установка зависимостей:
<br>1. composer install
# 2. .env
В корень приложения скопировать файл .env.example с заменой имени на .env.
Для связи с базой данных используется PDO, данные указываются в env-файле. Пример строк:
<br>DB_DSN="mysql:host=127.0.0.1;dbname=task"
<br>DB_USERNAME="root"
<br>DB_PASSWORD="root"
<br>После этого приложение сможет работать.
# 3. Database и versions
При разработке использовались MySQL-8.0 под windows 10, PHP_8.1, Slim v4 и Vue 3 версии.
Таблица с именем comments и колонками id, name, text, published_time и published_date.
Также .htaccess лежащий в корне предназначем для web-сервера apache. Я использовал Apache-2.4.
# 4. Технические приколы
Логика взаимодействия с базой данных вынесена в класс commentMapper. На фронте стоит Vue который fetch`ем получает и отправляет запросы на сервер по api. Шаблонизатор twig я не использовал, поскольку он тут и не нужен. Вместо двойных кавычек Vue я использую тег v-html, так как render slim-а перекрывает интерполяцию vue.
Из сторонних библиотек в данном проекте присутствует лишь "vlucas/phpdotenv", от использования сторонних ORM пришлось отказаться в целях экономии времени(да и запросов всего несколько штук).
Простенькая своя капча реализована на фронте. Дизайн тоже простенький.
