<?php

namespace NameChecker;

use NameChecker\Exceptions\NCException;

class NameChecker{

	/**
	 * Work around to use anonymous functions as constant.
	 *
	 * @return array returns the accepted medias
	 */
	private static function medias(){
		return [
			'facebook' => [
				'phone' => [
					'url' => 'https://mbasic.facebook.com/login/identify/?ctx=recover',
					'options' => function($v){
						return ['method' => 'POST', 'data' => ['lsd'=>/*AVpNLmOr*/'AVpgIho2','email'=>$v,'did_submit'=>'Search']];
					},
					'check' => function($c){
						return preg_match("/login_identify_search_error_msg/", $c) ? false : true;
					}
				],
				'email' => [
					'url' => 'https://mbasic.facebook.com/login/identify/?ctx=recover',
					'options' => function($v){
						return ['method' => 'POST', 'data' => ['lsd'=>/*AVpNLmOr*/'AVpgIho2','email'=>$v,'did_submit'=>'Search']];
					},
					'check' => function($c){
						return preg_match("/login_identify_search_error_msg/", $c) ? false : true;
					}
				],
				'id' => [
					'url' => 'https://graph.facebook.com/?id={id}',
					'is_valid' => function($v){
						return preg_match('/^[a-z\d.]{5,}$/i',$v) || is_numeric($v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/not exist/", $c) ? false : true;
					}
				]
			],
			'instagram' => [
				'id' => [
					'url' => 'https://instagram.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/im',$v) ? true : false;
					},
					'check' => function($c, $v){
						return preg_match("/@$v/i", $c) ? true : false;
					}
				]
			],
			'twitter' => [
				'id' => [
					'url' => 'https://twitter.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^@?(\w){1,15}$/',$v) ? true : false;
					},
					'check' => function($c, $v){
						return preg_match("/@$v/i", $c) || preg_match("/account_suspended/", $c) ? true : false;
					}
				]
			],
			'github' => [
				'id' => [
					'url' => 'https://api.github.com/users/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/created_at/", $c) ? true : false;
					}
				]
			],
			'slack' => [
				'id' => [
					'url' => 'https://{id}.slack.com/',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/fire 404/", $c) ? false : true;
					}
				]
			],
			'youtube' => [
				'id' => [
					'url' => function(){
						#dumped on https://livecounts.net/script.js		---		CTRL + F: ",channelKeys=["
						$keys = ['AIzaSyAm3hJqTq1L1wcz-cz_4zLUNs2PD37hLuY','AIzaSyBiwDi5co4t3Fopz9oEcfoisthYZz_kivM','AIzaSyC_oqi_gbXaI29dkJJMRs0a82OWcl-h3tU','AIzaSyDdck0LEAXOCBVKDpN4ZgxC0Gk6zBedOTM','AIzaSyAkedClIJENM-lKk5Hwziprb_E9G5bKopc'];
						return 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername={id}&key='.$keys[mt_rand(0, count($keys) -1)];
					},
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9\-]{1,}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/contentDetails/", $c) ? true : false;
					}
				]
			],
			'vimeo' => [
				'id' => [
					'url' => 'https://vimeo.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/exception_title/", $c) ? false : true;
					}
				]
			],
			'dailymotion' => [
				'id' => [
					'url' => 'https://api.dailymotion.com/user/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/error/", $c) ? false : true;
					}
				]
			],
			'bitbucket' => [
				'id' => [
					'url' => 'https://api.bitbucket.org/2.0/users/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/display_name/", $c) || preg_match("/team account/", $c) ? true : false;
					}
				]
			],
			'patreon' => [
				'id' => [
					'url' => 'https://www.patreon.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Looks like you got lost/", $c) ? false : true;
					}
				]
			],
			'telegram' => [
				'id' => [
					'url' => 'https://telegram.me/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/tgme_page_photo_image/", $c) || preg_match("/tgme_page_title/", $c) ? true : false;
					}
				]
			],
			'gravatar' => [
				'email' => [
					'url' => function($email){
						return 'https://www.gravatar.com/avatar/'.md5(strtolower(is_array($email) ? $email['email'] : $email)).'?d=404';
					},
					'is_valid' => function($v){
						return self::is_email($v);
					},
					'check' => function($c){
						return preg_match("/404 Not Found/", $c) ? false : true;
					}
				]
			],
			'myanimelist' => [
				'id' => [
					'url' => 'https://myanimelist.net/animelist/{id}/load.json?offset=0&status=10',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/invalid request/", $c) ? false : true;
					}
				]
			],
			'kitsu' => [
				'id' => [
					'url' => 'https://kitsu.io/api/edge/users?filter%5Bslug%5D={id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/pastNames/", $c) ? true : false;
					}
				]
			],
			'flickr' => [
				'id' => [
					'url' => 'https://www.flickr.com/photos/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/statusCode_404/", $c) ? false : true;
					}
				]
			],
			'pastebin' => [
				'id' => [
					'url' => 'https://pastebin.com/ajax/check_username.php',
					'options' => function($v){
						return ['method' => 'POST', 'data' => ['action'=>'check_username','username'=>$v]];
					},
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]{3,20}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/green/", $c) ? false : true;
					}
				]
			],
			'imgur' => [
				'id' => [
					'url' => 'https://api.imgur.com/account/v1/accounts/{id}?client_id=546c25a59c58ad7',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Not Found/", $c) ? false : true;
					}
				]
			],
			'medium' => [
				'id' => [
					'url' => 'https://medium.com/@{id}/',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/medium:\/\/\@/", $c) ? true : false;
					}
				]
			],
			'twitch' => [
				'id' => [
					'url' => 'https://api.twitch.tv/kraken/channels/{id}',
					'options' => function($v){
						return ['headers' => ['client-id: jzkbprff40iqj646a697cyrvl0zt2m6']];
					},
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Not Found/", $c) ? false : true;
					}
				]
			],
			'behance' => [
				'id' => [
					'url' => 'https://www.behance.net/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/errors\-title/", $c) ? false : true;
					}
				]
			],
			'spotify' => [
				'id' => [
					'url' => 'https://open.spotify.com/user/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Whoops\!/", $c) ? false : true;
					}
				]
			],
			'dribbble' => [
				'id' => [
					'url' => 'https://dribbble.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Whoops/", $c) ? false : true;
					}
				]
			],
			'soundcloud' => [
				'id' => [
					'url' => 'https://soundcloud.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/Hear the world/", $c) ? false : true;
					}
				]
			],
			'reddit' => [
				'id' => [
					'url' => 'https://www.reddit.com/user/{id}/',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/either deleted\, banned\, or doesn/", $c) ? false : true;
					}
				]
			],
			'steam' => [
				'id' => [
					'url' => 'https://steamcommunity.com/id/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/An error was/", $c) ? false : true;
					}
				]
			],
			'vk' => [
				'id' => [
					'url' => 'https://vk.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-zA-Z0-9_\-]+$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/404 Not Found/", $c) ? false : true;
					}
				]
			]
		];
	}
#var_dump($c);die;
	/**
	 * Stores last result for verbose methods you can query.
	 * @var $lastResult bool
	 */
	static $lastResult;

	/**
	 * Identifies the name of social media by slug.
	 *
	 * @param string $label name of social media
	 * @return string returns the found name
	 */
	static function normalize_label($label){
		switch($label){
			case 'fb':
				return 'facebook';
			case 'ig':case 'insta':
				return 'instagram';
			case 'tw':
				return 'twitter';
			case 'gh':
				return 'github';
			case 'yt':
				return 'youtube';
			case 'vm':
				return 'vimeo';
			case 'dm':
				return 'dailymotion';
			case 'pt':
				return 'patreon';
			case 'gv':
				return 'gravatar';
			case 'tl':
				return 'telegram';
			case 'mal':
				return 'myanimelist';
			case 'ks':case 'hummingbird':
				return 'kitsu';
			default:
				return $label;
		}
	}

	public function __construct(...$args){
		return $this;
	}

	/**
	 * Method for make dynamics methods possible if class has instance.
	 *
	 * @param string $label name of method
	 * @param array $args all params passed to method
	 * @return object returns instance of object
	 */
	public function __call($label, $args){
		$this->__callStatic($label, $args);
		return $this;
	}

	/**
	 * Dynamic methods if has static called class.
	 *
	 * @param string $label name of method
	 * @param array $args all params passed to method
	 * @throws NCException when the args has not passed
	 * @return static|object returns instance of object
	 */
	public static function __callStatic($label, $args){
		if($label == 'domain'){
			self::$lastResult = gethostbyname($args[0]) != $args[0];
			return __CLASS__;
		}
		$label = self::normalize_label(strtolower($label));
		$medias = self::medias();
		if (!isset($medias[$label]))
			throw new NCException('Social name search not supported.');
		switch(count($args)){
			case 2:
				self::find($label, $args[0], trim($args[1]));
				break;
			case 1:
				self::find($label, count($medias[$label]) == 1 ? array_keys($medias[$label])[0] : (self::is_email($args[0]) ? 'email' : 'id'), trim($args[0]));
				break;
			default:
				throw new NCException('No parameters for searching a '.$label.' account.');
		}
		return __CLASS__;
	}

	/**
	 * Method verbose. Used when you need that return:
	 * TRUE		= founded / exist
	 * FALSE	= not found / don't exist
	 * NULL		= invalid ID or equivalent
	 *
	 * @return bool
	 */
	public static function isThere(){
		return self::$lastResult;
	}

	/**
	 * Method verbose. Used when you need that return:
	 * TRUE		= not found / don't exist
	 * FALSE	= founded / exist
	 * NULL		= invalid ID or equivalent
	 *
	 * @return bool
	 */
	public static function isAvailable(){
		return self::$lastResult === null ? null : !self::$lastResult;
	}

	/**
	 * Basically, check if the arguments are valid, look in the respective media method and validate the return value.
	 *
	 * @param array $args 
	 * @throws NCException when search method not supported or invalid params 
	 * @return void
	 */
	private static function find(...$args){
		$medias = self::medias();
		switch (count($args)){
			case 3:
				list($label, $method, $value) = $args;
				if (isset($medias[$label][$method])){
					$options = ['method' => 'GET', 'type' => 'html', 'url' => self::parse_endpoint($medias[$label][$method]['url'], [$method => $value])];
					if ((isset($medias[$label][$method]['is_valid']) && $medias[$label][$method]['is_valid']($value)) || ($method != 'id' && method_exists(get_class(), 'is_'.$method) && call_user_func([get_class(), 'is_'.$method], $value))){
						if (isset($medias[$label][$method]['options'])){
							if (!is_array($medias[$label][$method]['options']) && !is_callable($medias[$label][$method]['options']))
								throw new NCException('Search parameter "options" accept types: array or anonymous function.');

							$options = array_merge($options, is_callable($medias[$label][$method]['options']) ? $medias[$label][$method]['options']($value) : $medias[$label][$method]['options']);
						}
					}else{
						return self::$lastResult = null;
						
					}
				}else{
					throw new NCException('Search method not supported.');
				}

				if (!isset($medias[$label][$method]['check']))
					throw new NCException('Search parameter "check" has not been declared.');
				if (!is_callable($medias[$label][$method]['check']))
					throw new NCException('Search parameter "check" must be an anonymous function.');

				return self::$lastResult = $medias[$label][$method]['check'](self::curl($options), $value);
			default:
				throw new NCException('Invalid search parameters.');
		}
	}

	/**
	 * Replace method endpoint with correct values.
	 *
	 * @param string $endpoint URL of method endpoint
	 * @param array $values all values to replace in URL
	 * @return string
	 */
	private static function parse_endpoint($endpoint,$values=[]){
		return str_replace(array_map(function($key){
			return '{'.$key.'}';
		}, array_keys($values)), array_values($values), is_callable($endpoint) ? $endpoint($values) : $endpoint);
	}

	/**
	 * Verify phone number is valid as Brazilian format....
	 *
	 * @param string $number
	 * @return bool
	 */
	private static function is_phone($number=0){
		$c = preg_replace("/[^0-9]/",'',$number);
		return $c && strlen($c)>9 && preg_match("/^[1-9]{2}9?[0-9]{8}$/",$c) ? true : false;
	}

	/**
	 * Verify e-mail is valid.
	 *
	 * @param string $email
	 * @return bool 
	 */
	private static function is_email($email=''){
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU',$email) ? true : false;
	}

	/**
	 * Navigates the endpoints and returns the result.
	 *
	 * @param array $options 
	 * @throws NCException when $options is invalid
	 * @return mixed 
	 */
	private static function curl($options=[]){
		if (!isset($options['url']))
			throw new NCException('CURL options need URL parameter.');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $options['url']);
		if (isset($options['header']) && $options['header'])
			curl_setopt($ch, CURLOPT_HEADER, 1);
		if (isset($options['headers']) && is_array($options['headers']))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIESESSION, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/nc_cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/nc_cookie.txt');
		if (isset($options['referrer']))
			curl_setopt($ch, CURLOPT_REFERER, $options['referrer']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		if (isset($options['method'],$options['data']) && $options['method'] == 'POST'){
			curl_setopt($ch, CURLOPT_POST, 1);				 
			curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($options['data']) ? http_build_query($options['data']) : $options['data']);
		}
		$data = curl_exec($ch);
		curl_close($ch);
		if (isset($options['type']) && $options['type'] == 'json'){
			return json_decode($data,true);
		}else{
			return $data;
		}
	}
}