RESTful api with Symfony2
=================================

A RESTful api sample with Sumfony2

## Installation

[PHP](https://php.net) 5.6+, a database server, and [Composer](https://getcomposer.org) are required

### Clone the repository

`git clone https://github.com/tirjok/book-store.git api`

### Install dependencies

See [Composer](https://github.com/composer/composer) documentation

`cd api`

`composer install`

### Added FOSUserBundle
See [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) documentation


### Added lexik/jwt-authentication-bundle 
See [lexik/jwt-authentication-bundle](https://github.com/lexik/LexikJWTAuthenticationBundle) documentation

### Configuration
Go to `app/config`

`cp parameters.yml.dist parameters.yml`

Set parameters according to your environment.

``` bash

$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force

```

To get token, first create a user. FOSUserBundle comes with nice command to create user.

``` bash
php app/console fos:user:create
```

Usage
-----
#### 1. Obtain the token

```bash
curl -X POST http://localhost:8000/api/login_check -d _username=johndoe -d _password=test
```

If it works, you will receive something like this:

```json
{
   "token" : "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MzQ3Mjc1MzYsInVzZXJuYW1lIjoia29ybGVvbiIsImlhdCI6IjE0MzQ2NDExMzYifQ.nh0L_wuJy6ZKIQWh6OrW5hdLkviTs1_bau2GqYdDCB0Yqy_RplkFghsuqMpsFls8zKEErdX5TYCOR7muX0aQvQxGQ4mpBkvMDhJ4-pE4ct2obeMTr_s4X8nC00rBYPofrOONUOR4utbzvbd4d2xT_tj4TdR_0tsr91Y7VskCRFnoXAnNT-qQb7ci7HIBTbutb9zVStOFejrb4aLbr7Fl4byeIEYgp2Gd7gY"
}
```

#### 2. Use the token
By default only the authorization header mode is enabled : `Authorization: Bearer {token}`

### Endpoints

#### Authors Resources

-  `/api/v1/authors GET`

Response `200`
```json
{
    "authors": [
        {
            "author_id": 1,
            "name": "J. R. R. Tolkien",
            "email": "tolkien@universe.com",
            "birthday": null,
            "books": [
                {
                    "book_id": 1,
                    "name": "The Lord of the Rings",
                    "price": 821,
                    "description": "More than 100 million copies",
                    "isbn": "234234"
                }
            ]
        }
    ]
}
```

-  `/api/v1/authors POST`

Request

```json
{
  "name": "J. R. R. Tolkien",
  "email": "tolkien@universe.com",
  "birthday": "1892-01-03"
}
```
Response `201`

```json
{
    "author_id": 1,
    "name": "J. R. R. Tolkien",
    "email": "tolkien@universe.com",
    "birthday": {
        "date": "1892-01-03",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "books": []
}
```

- `/api/v1/authors/{id} GET` [Public]

Response `200`

```json
{
    "author_id": 1,
    "name": "J. R. R. Tolkien",
    "email": "tolkien@universe.com",
    "birthday": {
        "date": "1892-01-03",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "books": []
}
```

- `/api/v1/authors/{id} PUT`

Request

```json
{
  "name": "J. R. Tolkien",
  "email": "tolkien@universe.com",
  "birthday": "1892-01-03"
}
```

Response `200`

```json
{
    "author_id": 1,
    "name": "J. R. Tolkien",
    "email": "tolkien@universe.com",
    "birthday": {
        "date": "1892-01-03",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "books": []
}
```

- `/api/v1/authors/{id} DELETE`

Response `204`


#### Books Resources

-  `/api/v1/books GET`

Response `200`
```json
{
    "books": [
        {
            "book_id": 1,
            "name": "The Lord of the Rings",
            "price": 821,
            "description": "More than 100 million copies",
            "isbn": "234234",
            "author": {
                "author_id": 1,
                "name": "J. R. R. Tolkien",
                "email": "tolkien@universe.com",
                "birthday": null
            }
        }
    ]
}
```

-  `/api/v1/books/{id} GET` [Public]

Response `200`

```json
{
    "book_id": 1,
    "name": "The Lord of the Rings",
    "price": 821,
    "description": "More than 100 million copies",
    "isbn": "234234",
    "author": {
        "author_id": 1,
        "name": "J. R. R. Tolkien",
        "email": "tolkien@universe.com",
        "birthday": null
    }
}
```

-  `/api/v1/books POST`

Request

```json
{
	"name": "ABS",
	"price": 300,
	"description": "Test book",
	"isbn": "22333",
	"author_id": 1
}
```

Response `201`

```json
{
    "book_id": 5,
    "name": "ABS",
    "price": 300,
    "description": "Test book",
    "isbn": "22333",
    "author": {
        "author_id": 1,
        "name": "Newton",
        "email": "newton@universe.com",
        "birthday": null
    }
}
```

- `/api/v1/books/1 DELETE`

Response `204`
