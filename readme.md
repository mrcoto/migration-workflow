# Migration Workflow Package for Laravel

Why to use this package? 

One little problem with Laravel when running migrations and seeds, is the execution order on those files.

For example, when you run:
```bash
php artisan migrate:fresh
php artisan db:seed
```
First, migration files are executed, and then seeder classes are executed.

This package allows you to control the execution order of those migration and seeder files.
You can:
- Define the order between migration and seed files
- Add a version tag to your migration workflow
- Store in database your deployed migration workflows (same as default laravel migrations files stored in database when you migrate files with ```php artisan migrate```).

## Documentation

|Versión|Link|
|---|---|
|*2.0*|[https://mrcoto.github.io/migration-workflow-docs/2.0/](https://mrcoto.github.io/migration-workflow-docs/2.0/)|
|*1.4*|[https://mrcoto.github.io/migration-workflow-docs/1.4/](https://mrcoto.github.io/migration-workflow-docs/1.4/)|

----------------------------

All rights reserved Innlab@2019 (Package developed by José Espinoza)