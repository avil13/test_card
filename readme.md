# тестовое задание

```
Необходимо создать 3 миграции: пользователи, кошельки пользователей, транзакции

Ограничения:
Пользователь может иметь несколько кошельков
Транзакция привязана к кошельку
Существует несколько ролей пользователей и кошельков

Реализовать методы:
Получения текущего баланса пользователя, баланса за n-ую дату
Получения истории транзакций
Перевода средств одного пользователя другому
Пополнения кошелька
```

### для того что бы проверить проект:
- указать данные для соединения с БД
- далее в консоли:
    - npm install
    - composer install
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed

Теперь можно проверять в браузере

##### Так как это тест, и времени было не так много, то есть несколько нюансов.

У пользователей должны быть группы, но не сказано для чего они нужны, поэтому функционала им не добавлялось.

Пользователей создавать нельзя, но уже создаются 3 пользователя:

| Имя | Пароль | email |
|---|---|---|
| user1 | user1 | user1@mail.ru | 
| user2 | user2 | user2@mail.ru | 
| admin | admin | admin@mail.ru | 


По умолчанию у пользователя есть один кошелек который считается главным.
В списке отмечен значком в восклицательным знаком.
Его отличие от других в том что если пользователю переводят деньги другие пользователи, то они будут зачислены именно на него.


