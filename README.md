# PHP REST client
[![Build Status](https://travis-ci.org/sc0rp10/rest-client.svg?branch=master)](https://travis-ci.org/sc0rp10/rest-client)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bbfbf2a6-d8b8-49fe-9c03-5dc3e4eb1f29/big.png)](https://insight.sensiolabs.com/projects/bbfbf2a6-d8b8-49fe-9c03-5dc3e4eb1f29)

This is simple and extendable REST client.

## Basic sage

```php
use Sc\RestClient\Client\Client;
use Sc\RestClient\ResponseParser\JsonResponseParser;

$client = new Client('https://api.foo.bar', new JsonResponseParser());

$resourse = 'zombies';

// retrieve all objects (will produce GET /zombies/)
$client->getAll($resourse);

// create a new resourse (will produce POST /zombies/ with following data)
$created = $client->create($resourse, [
    'name' => 'Shaun',
    'age' => 29,
]);
// 'create' API should return data or 'Location' header with created resource URI

$id = $created['id'];

// retrieve single object (will produce GET /zombies/123/)
$client->get($resourse, $id);

// update full resourse (will produce PUT /zombies/123/)
$updated = $client->update($resourse, $id, [
	'name' => 'Shaun',
	'age' => 30,
]);

// update resourse partially (will produce PATCH /zombies/123/)
$updated = $client->update($resourse, $id, [
	'age' => 31,
]);

// delete resourse (will produce DELETE /zombies/123/)
$client->delete($resourse, $id);

```
## Change response format
```php
use Sc\RestClient\Client\Client;
use Sc\RestClient\ResponseParser\XmlResponseParser;

$client = new Client('https://api.foo.bar', new XmlResponseParser('root_tag_name'));
```
### Implementing own response parser
Just implement `Sc\RestClient\ResponseParser\ResponseParserInterface#parseResponse` and play with response

## Custom api authentification
```php
use Sc\RestClient\Client\Client;
use Sc\RestClient\ResponseParser\JsonResponseParser;
use Sc\RestClient\AuthenticationProvider\QueryParameterProvider;
use Sc\RestClient\AuthenticationProvider\HeaderProvider

$client = new Client('https://api.foo.bar', new JsonResponseParser());
$client->useAuthenticator(new QueryParameterProvider('api_key', 'abcd1234'));
// each request will be followed with ?api_key=abcd1234 query string

// or
$client->useAuthenticator(new HeaderProvider('X-Auth-Header', 'abcd1234'));
// each request will be followed with header "X-Auth-Header: abcd1234"
```
### Implementing own authenticator
`Sc\RestClient\AuthenticationProvider\AuthenticationProviderInterface#addAuthentificationInfo` will play with original request and modify it according with your requirements such as X.509 certificates

## Request signing
@TODO
