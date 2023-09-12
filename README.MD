## INFO

Writed 11/09/2023 with PHPStorm.
The symfony 6 framework and console functions have been used to pregenerate some classes (entity, crud, fixtures).  


### Init && Composer 

Composer commands executed

```bash
composer create-project symfony/website-skeleton .
composer require annotations
composer require fakerphp/faker
composer require orm-fixtures --dev 
composer require twig/markdown-extra
composer require league/commonmark
composer require symfony/webpack-encore-bundle
```


## Comments
### ToDo`s


* Better structure and lighten code.
* Testing
* Dynamic form for the three steps according to input data. 
* Form phase control in separate class. 
* Design. 
* Move some logic to specialized symfony classes. 
* Translations 
* Domain object "DateRange" or similar
* Migration and fixtures to docker

### Struct

Classic MVC. A hexagonal-based structure would have been convenient but I did not have enough confidence to do it in the indicated time (events against some task queue, read-write database separation, etc.).

A division similar to the following would have been advisable

```bash
app/controllers #puntos de entrada
src/Shared
src/Drivers/{Domain,Infrastructure,Aplication}
src/Vehicles/{Domain,Infrastructure,Aplication}
src/Trips/{Domain,Infrastructure,Aplication}
```

### Commands

Basic fixtures maked

```bash
php bin/console doctrine:fixtures:load
```

## RUN

```bash
docker-compose up -d
```

Migrations and fixtures must be executed later

```bash
docker exec -u root -it `docker ps | grep -i symfony_test_php_ | awk '{print $1}'`  php bin/console doctrine:migrations:migrate --no-interaction
docker exec -u root -it `docker ps | grep -i symfony_test_php_ | awk '{print $1}'`  php bin/console doctrine:fixtures:load --no-interaction
```