1. Клонировать репозиторий: `git clone [ссылка]`
2. Установить зависимости: `composer install`
3. Создать файл окружения: `cp .env.example .env`
4. Сгенерировать ключ: `php artisan key:generate`
5. Настроить подключение к БД в файле `.env` (параметры DB_DATABASE, DB_USERNAME, DB_PASSWORD).
6. Выполнить миграции и сидеры: `php artisan migrate --seed`
7. Запустить сервер: `php artisan serve`

### Данные для входа в админ-панель:
* Email: `admin@edu.com`
* Пароль: `course2026`