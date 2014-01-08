Dotclear
========

Dotclear XML-RPC client library

[![Build Status](https://travis-ci.org/Spir/Dotclear.png?branch=master)](https://travis-ci.org/Spir/Dotclear)


Quick example
----

```php
$blogId = 'blog-id';
$url = 'http://myblog.com/xmlrpc/'.$blogId;
$username = 'username';
$password = 'password';

$client = new Spir\Dotclear\Client($url.$blodId, $username, $password, $blodId);

var_dump($client->getPosts(0));
var_dump($client->getCategories());
var_dump($client->getAuthors());
```
