### How it works

This is a PHP web application that scrapes the BBC news homepage (http://www.bbc.co.uk/news/) and returns a 
JSON array of the most popular shared articles table. The JSON array also contains the size and the most used word of each article.

### Objective

Exercise the use of xpath queries and also have some fun.

### Version
1.0

### Requirements

In order to run this app you'll need:

* [PHP 5.5](http://php.net/manual/en/migration55.changes.php)
* [PHP Curl](http://php.net/manual/en/book.curl.php)
* [PHPUnit](https://phpunit.de/index.html)

### Running the App

Call the index.php file from a web browser:

### Testing the App

Enter the tests folder `Tests/` and run PHPUnit on the command line:

- To run all tests:
```sh
$ phpunit .
```
