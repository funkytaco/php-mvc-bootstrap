# php-mvc

A modular 'no framework' MVC framework in PHP5.

Build Status - Untracked

## Installation

```sh
$ composer install
```

```sh
$ composer install-mvc
```
## Usage
To run a development server on localhost port 8000:
```sh
$ composer serve
```
The port can be changed in **DevTasks.php**, which is a Composer scripts PHP file.

## Usage ##
### 1) Create your own View ###
- add a template view in **app/Views/** by default, this is a [Mustache]() template. (It is possible to change the rendering engine).
    - add a controller in src/Controllers/ which uses the view.
    - add a route in **app/CustomRoutes.php** that uses the controller.

    For further templating information, [mustache.php] has a good primer on how to pass in your data. If you don't like Mustache, then [No Framework Templating], explains how to replace the "Renderer".

### 2) Create your own Controller ###
- add a controller in **app/Controllers/** [(Example Controller)](https://gist.github.com/funkytaco/87fd34b5ef863ebbc120)
    - For the controller to be used, it must be used by a route in  **app/Routes.php**
    - Reference a view to load in the controller function, if applicable.
    - `$this->data` is how your model data will be accessed by the controller, and shared with the view.


### 3) Create your own Model ###
 - You can put your model in **app/Traits/** or **app/Models** for models which will not be re-used.
    - The **$conn PDO** connection is be passed into the controller.
 - The PDOWrapper class `uses` the namespace of your Trait file, e.g.,
`use \Main\Traits\MyQueryData`. Since this class is now loaded in the class all of its functions are available to the parent class.
- e.g. `getUsers()` in our traits file is accessible as `$conn->getUsers()`.

    ####To use a MySQL/Postgres/Other Database:####
- In `src/Traits/QueryData.php`
    - add your query functions in  (I will explain how to use these functions in your view)
    - uncomment `$conn = $injector->make('\Main\PDO');` . It must stay below the `$injector->define` for PDO.
- In your controller:
    -  **use \Main\PDO;** and comment/remove **use \Main\Mock\PDO;**
- In `Config.php`:
    - `$dbtype` should be set to *mysql* or *postgres*
    - You can add other types supported by PDO, as this is just a PDO instantiation.
- Stub out your database queries:
    - create a Foo_Module.php in **src/Modules** and include it like the example Date_Module class.




***
*Additional Info*

##Tree##
public assets directory:
Any CSS/JS/media assets MUST go in public/assets

    public
    ├── assets
    └── index.php


Source directory:
This is the core of our MVC framework.

    src
    ├── Bootstrap.php
    ├── Dependencies.php
    ├── MimeTypes.php
    ├── Mock
    │   ├── PDO.php
    │   └── Traits
    │       └── QueryData.php
    ├── Modules
    │   └── Date_Module.php
    ├── Renderer
    │   ├── MustacheRenderer.php
    │   └── Renderer.php
    ├── Routes.php
    └── Static
        └── Error.php
Test directory:

    test
    ├── bootstrap.php
    └── src
        ├── Controllers
        │   └── IndexController_Test.php
        └── Mock
            └── PDO_Test.php


***

## Components

Components
  - [Bootstrap] for front-end development (in bootstrap branch)
  - [Composer] for dependency management and project setup (i.e. post installation script events)
  - [whoops] for error handling
  - [Klein.php] for routing
  - [mustache.php] for templating
  - [Auryn] for IoC dependency injection

Change out these components for others (i.e. replace [mustache.php] with [handlebars.php]) by reading through [No Framework] for PHP.

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History
  - v 0.7.5 stripped out Bootstrap from master branch and moved it to a bootstrap branch.
  - v 0.7.4 PHPUnit Travis-CI tests. Callout CSS. PDO Config file added. PDO structure and file name changes. Code cleanup for Routes.php
  - v 0.7.3 Updated license. PDO wrapper changes.
  - v 0.7.2 Initial commit



[Bootstrap]:http://www.getbootstrap.com/
[Composer]:https://getcomposer.org/
[whoops]:https://github.com/filp/whoops/
[Klein.php]:https://github.com/chriso/klein.php/
[mustache.php]:https://github.com/bobthecow/mustache.php
[handlebars.php]:https://github.com/XaminProject/handlebars.php/
[Auryn]:https://github.com/rdlowrey/Auryn/
[No Framework]:https://github.com/PatrickLouys/no-framework-tutorial/
[No Framework Templating]: https://github.com/PatrickLouys/no-framework-tutorial/blob/master/09-templating.md
[@PatrickLouys]:https://github.com/PatrickLuoys/
