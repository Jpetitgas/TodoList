Prerequisites
Have installed the project in local by following README.md instructions :
https://github.com/Jpetitgas/TodoList/blob/main/README.md.

See the Proposed improvement plan: 
https://github.com/Jpetitgas/TodoList/projects/1

Testing

PHPUnit in local

To launch tests in local you need to load data fixtures in a test database.
First, create a .env.test.local file.
Inside, just setup the DATABASE_URL environment variable with your local db credentials.
From your terminal with your own local environment :

php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
php bin/console doctrine:fixtures:load --env=test

Code Quality

By contributing to this project, please ensure to maintain a Grad A quality level from Codacy.
phpcs is using in local, follow PSR rules, at the minimum PSR-1, PSR-2 and PSR-4.
In addition, respect as much as possible the SOLID principles.

How to do your changes
First, click on the link to access to the Project Improvement Plan.
Check if your change is not already cover by an ongoing issue.
If not, create your own issue to discuss around it.
When the issue is well define, you can do your changes or add a new feature.
The project deployment branch is "main", so please, never commit on it.
The branch for contribute is "develop"
Before your first commit, test your code, check the quality !
If all is green, you can push your branch and submit a pull request ! 
Now you just have to wait for it to be reviewed and accepted.