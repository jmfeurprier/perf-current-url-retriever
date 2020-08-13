Current URL retriever
=====================

Allows to retrieve current URL.

## Installation

```shell script
composer require perf/current-url-retriever
```

## Usage

### Concrete usage

```php
use perf\CurrentUrlRetriever\CurrentUrlRetriever;

$retriever = new CurrentUrlRetriever();

$url = $retriever->retrieve();

echo $url; // Will print something like "https://some.site.com/some/path?foo=bar"
```

### Static usage

```php
use perf\CurrentUrlRetriever\CurrentUrlRetriever;

$url = CurrentUrlRetriever::create()->retrieve();

echo $url; // Will print something like "https://some.site.com/some/path?foo=bar"
```