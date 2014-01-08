Dotclear
========

Dotclear XML-RPC client library


Quick example
----

```php
$client = new Spir\Dotclear\Client('http://myblog.com/xmlrpc/', 'username', 'password', 'blog-id');
// Get the last 10 posts
$lastPost = $client->getPosts();
```
