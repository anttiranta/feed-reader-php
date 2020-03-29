# PHP Symfony task: feeds reader application

Task is to create a PHP application that is a feeds reader. The app can read feed from multiple sources and store them to database. Sample feeds http://www.feedforall.com/sample-feeds.htm.

## Requirements

- As a developer, I want to run a command which help me to setup database easily with one run. 
- As a developer, I want to run a command which accepts the feed urls (separated by comma) as argument to grab items from given urls. Duplicate items are accepted. 
- As a developer, I want to see output of the command not only in shell but also in pre-defined log file. The log file should be defined as a parameter of the application. 
- As a user, I want to see the list of items which were grabbed by running the command line above, via web-based. I also should see the pagination if there are more than one page. The page size is a configurable value. 
- As a user, I want to filter items by category name on list of items. 
- As a user, I want to create new item manually via a form. 
- As a user, I want to update/delete an item
- Implementation should be covered with unit tests

## Under progress

- There are still some open todos
- As a developer, I want to run a command which accepts the feed urls (separated by comma) as argument to grab items from given urls. Duplicate items are accepted. 

## Installation

Set local environment variables

```
$ cp .env.dist .env
$ cp .env.test.dist .env.test
```

Install dependencies:

```
$ composer install
```

Run:

```
$ ./bin/console server:run
```

Open app in browser: http://localhost:8000

## GraphQL API

In an update to the application there was an addition to the application that provides support for a GraphQL API using the Overblog GraphQL bundle. 

In development mode the GraphiQL client is available in the URL http://localhost:8000/graphiql

### React

Frontend is built with React and can be found here: https://github.com/anttiranta/feeds-reader-frontend
