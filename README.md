## Build simple Rest apps in Laravel

It's implemented a simple Rest API Endpoint "/tasks":

### There are some classes that have the following functionality:

- TaskController - Entry Point for the /tasks router.
- Authenticate - Has handle method for auth handling.
- Task - The model gets data from 'tasks' DB table.
- TaskResource - This object has main settings for Model.
- TaskCollection - List of Models.

### Here is a list of CLI commands that have to be run after the repository is downloaded:
```
php artisan migrate
php artisan db:seed
```
### List of API commands:

php artisan route:list --path=api
<p align="center"><a href="https://i.imgur.com/nzzFxI6.png" target="_blank"><img src="https://i.imgur.com/nzzFxI6.png"></a></p>

### Here is an example of getting all items with filtering:

http://laravel9r.local/api/tasks?filter[status]=todo&filter[0][]=priority&filter[0][]=>=&filter[0][]=2&filter[1][]=priority&filter[1][]=<=&filter[1][]=5&filter[title]=Miss&sort[priority]=desc&sort[completed_at]=asc&sort[created_at]=asc&email=tyree04@example.com&password=qweqw
<p align="center"><a href="https://i.imgur.com/qMwE4so.png" target="_blank"><img src="https://i.imgur.com/qMwE4so.png"></a></p>
