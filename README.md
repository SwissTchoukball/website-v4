# Swiss Tchoukball website

The current Swiss Tchoukball website was built in 2004 by Romain Schmocker using PHP. Since then some additions and improvements have been made, but the code is still based on what was written in 2004. PHP has evolved, so have coding practices. Therefore a lot of improvements can be brought and we hope that using git and GitHub will help going in that direction.

## Local setup (work in progress)
### Composer
* [Install composer](https://getcomposer.org/doc/00-intro.md)
* Install dependencies by running `php composer.phar install`
  * If you changed composer filename or filepath, the command could be something like `php bin/composer install`

### PHP config
* Duplicate `config-sample.php` and rename it `config.php`
* Fill the necessary values in `config.php` (TODO: elaborate)

### Apache config
* `ServerName` must be `localhost` in the `VirtualHost` config (port number is arbitrary)
  * If you want to set another `ServerName`, than also change the value in `config.php`
* `LoadModule rewrite_module` must be uncommented in `httpd.conf`

### MySQL config
TODO