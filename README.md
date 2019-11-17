# name-checker &middot; [![GitHub license](https://img.shields.io/badge/license-MIT-lightgrey.svg)](LICENSE) [![Latest Version](https://img.shields.io/github/release/jco666/name-checker.svg)](https://github.com/jco666/name-checker/releases) [![Latest Stable Version](https://img.shields.io/packagist/v/attla/name-checker)](https://packagist.org/packages/attla/name-checker) [![Total Downloads](https://img.shields.io/packagist/dt/attla/name-checker.svg)](https://packagist.org/packages/attla/name-checker)

### :ledger: Social and domain name availability search.

#### :squirrel: [Investigate the live demo](https://nchecker.000webhostapp.com/)

## Installation

Install the latest version with:

```bash
composer require attla/name-checker
```

###### If you don't have composer, [learn how to install](https://getcomposer.org/)

## Usage

```php
require_once 'vendor/autoload.php';

use NameChecker\NameChecker;
use NameChecker\Exceptions\NCException;

try{
	$NameChecker = new NameChecker();

	# Social medias names
	echo '<pre>';
	echo "My github is available ?\n";
	var_export($NameChecker->github('jco666')->isAvailable());

	echo "\nMy github exist ?\n";
	var_export($NameChecker->isThere());

	# Domain names
	echo "\n\n\nMy domain is available?\n";
	var_export($NameChecker->domain('lsdev.cf')->isAvailable());

	echo "\nMy domain exist ?\n";
	var_export($NameChecker->isThere());
}catch(NCException $e){
	echo $e->getMessage(),
	'<br>',
	$e->error_trace;
}
```
## More example usage
* **Call types**
```php
# Instantiated Class
$NameChecker = new NameChecker();
var_dump($NameChecker->github('jco666')->isAvailable());

echo '<br>';

# Static call
var_dump(NameChecker::github('jco666')::isAvailable());
```

* **Methods params**
```php
# NameChecker::method($type, $value)

# Search by ID
NameChecker::facebook('id', 'jco666')::isAvailable();
# or
NameChecker::facebook('jco666')::isAvailable();

# Search by email, 
NameChecker::facebook('email', 'fedhrm@gmail.com')::isAvailable();

# Search by fone
NameChecker::facebook('phone', 'fedhrm@gmail.com')::isAvailable();
```

## List of social medias accepted

|  | ID / Username | E-mail | Phone |
|:-:|:-:|:-:|:-:|
| Facebook | ✓ | ✓ | ✓ |
| Instagram | ✓ | ✗ | ✗ |
| Twitter | ✓ | ✗ | ✗ |
| Youtube | ✓ | ✗ | ✗ |
| twitch | ✓ | ✗ | ✗ |
| Vimeo | ✓ | ✗ | ✗ |
| Dailymotion | ✓ | ✗ | ✗ |
| spotify | ✓ | ✗ | ✗ |
| soundcloud | ✓ | ✗ | ✗ |
| Slack | ✓ | ✗ | ✗ |
| Github | ✓ | ✗ | ✗ |
| Bitbucket | ✓ | ✗ | ✗ |
| behance | ✓ | ✗ | ✗ |
| dribbble | ✓ | ✗ | ✗ |
| Flickr | ✓ | ✗ | ✗ |
| Patreon | ✓ | ✗ | ✗ |
| medium | ✓ | ✗ | ✗ |
| Telegram | ✓ | ✗ | ✗ |
| Gravatar | ✗ | ✓ | ✗ |
| MyAnimeList | ✓ | ✗ | ✗ |
| Kitsu | ✓ | ✗ | ✗ |
| Flickr | ✓ | ✗ | ✗ |
| Pastebin | ✓ | ✗ | ✗ |
| Imgur | ✓ | ✗ | ✗ |
| vk | ✓ | ✗ | ✗ |
| reddit | ✓ | ✗ | ✗ |
| steam | ✓ | ✗ | ✗ |


## License

[MIT](LICENSE) © [Nic.](http://ndev.cf)
