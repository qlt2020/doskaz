## Минимальные требования
- Postgresql 12 with postgis extension
- gotenberg docker container on ```localhost:3003```

## Pdf импорт
- Установить на сервере docker ci
- Развернуть проект gotenberg по документации ```https://gotenberg.dev/docs/6.x/install```
- Обновить host конфиг в ```TheCodingMachine\Gotenberg\Client``` расположенному по ```src/Infrastructure/services.yaml```

## Миграции бд
#### Перед запуском убедитесь что:
- удалена вручную ```PRIMARY KEY``` в таблицах ```levels``` и ```profile_completion_tasks```; 
- в таблице ```complaints```установите в уже существующих строках значение столбца ```object_id``` на ```NULL```;

## Команды
- Команда ```php bin/console app:blogs:truncate```  очищает (Truncate) таблицы, связанные с модулем Блог:
    - blog_comments
  - blog_posts
  - blog_categories
  При успешном завершении выведет в терминал сообщение об успешном завершении. В случае ошибки выведет в терминал
  сообщение о том, что произошла ошибка и откатит все изменения в БД.
  
### php bin/console app:objects:update-object-category
Команда заменяет старые иконки на новые

### php bin/console app:create-help-blog-category
Команда создает новую категорию "Помощь" в Медиатеке

- Команда ```php bin/console app:objects:update-object-category``` заменяет старые иконки на новые
