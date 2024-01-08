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



## Minimum requirements
- Postgresql 12 with postgis extension
- gotenberg docker container on ```localhost:3000```

## PDF Import
- Install Docker CI on the server.
- Deploy the Gotenberg project following the documentation at ```https://gotenberg.dev/docs/6.x/install```.
- Update the host configuration in ```TheCodingMachine\Gotenberg\Client``` located at ```src/Infrastructure/services.yaml```.



## Database Migrations
#### Before running, make sure that:
- Manually removed ```PRIMARY KEY``` in the ```levels``` and ```profile_completion_tasks tables```;
- In the ```complaints``` table, set the value of the ```object_id``` column to ```NULL``` for already existing rows;

## Commands
- The command php bin/console ```app:blogs:truncate``` clears (Truncate) tables related to the Blog module::
    - blog_comments
    - blog_posts
    - blog_categories
  Upon successful completion, it will display a success message in the terminal. In case of an error, it will
 output a message in the terminal that an error occurred and roll back all changes in the database.
  
### php bin/console app:objects:update-object-category
This command replaces old icons with new ones

### php bin/console app:create-help-blog-category
This command creates a new category "Help" in the Media Library.

- The command ```php bin/console app:objects:update-object-category``` replaces old icons with new ones
