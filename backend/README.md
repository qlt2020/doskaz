## Minimum requirements
- Postgresql 12 with postgis extension
- gotenberg docker container on localhost:3000

## Pdf import
- Install docker ci on the server;
- Deploy a project gotenberg in accordance with documentation https://gotenberg.dev/docs/6.x/install;
- Update host configuration in TheCodingMachine\Gotenberg\Client located at src/Infrastructure/services.yaml.

## Database migration
#### Before launching make sure that:
- you have manually deleted PRIMARY KEY in the tables called levels and profile_completion_tasks;
- you have set value of the object_id column to NULL in the already existing rows of the complaints table.

## Commands
- The php bin/console app:blogs:truncate command clears (Truncate) tables that are connected to the Blog module:
 - blog_comments;
 - blog_posts;
 - blog_categories Upon successful completion, it prints a success message to the terminal. In case of an error, it will display a message in the terminal stating that an error has occurred and will rollback all changes in the database.

### php bin/console app:objects:update-object-category
The command replaces old icons to the new ones

### php bin/console app:create-help-blog-category
The command creates new category called “Help” in the Media library
- The php bin/console app:objects:update-object-category command replaces old icons to the new ones



## Минимальные требования
- Postgresql 12 with postgis extension
- gotenberg docker container on ```localhost:3000```

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
