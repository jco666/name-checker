<?php

namespace NameChecker;

use NameChecker\Exceptions\NCException;

class NameChecker{

	/**
	 * Work around to use anonymous functions as constant.
	 *
	 * @return array returns the accepted medias
	 */
	static function medias(){
		return [
			'fb' => [
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
			'ig' => [
				'id' => [
					'url' => 'https://instagram.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/im',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/@$value/", $c) ? true : false;
					}
				]
			],
			'tw' => [
				'id' => [
					'url' => 'https://twitter.com/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^@?(\w){1,15}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/@$value/", $c) || preg_match("/account_suspended/", $c) ? true : false;
					}
				]
			],
			'gh' => [
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
			'sk' => [
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
			'yt' => [
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
			'vm' => [
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
			'dm' => [
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
			'bb' => [
				'id' => [
					#'url' => 'https://bitbucket.org/{id}',
					'url' => 'https://api.bitbucket.org/2.0/users/{id}',
					'is_valid' => function($v){
						return preg_match_all('/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/',$v) ? true : false;
					},
					'check' => function($c){
						return preg_match("/display_name/", $c) || preg_match("/team account/", $c) ? true : false;
					}
				]
			],
			'pt' => [
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
			'tl' => [
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
			'gv' => [
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
			]
		];
	}

	/**
	 * Stores last result for verbose methods you can query.
	 * @var $lastResult bool
	 */
	static $lastResult;

	/**
	 * Identifies the name of social media by accepting acronyms.
	 *
	 * @param string $label name of social media
	 * @param bool $sort for return a sort label
	 * @throws NCException when the name is not found
	 * @return string returns the found name
	 */
	static function normalize_label($label, $sort=true){
		switch($label){
			case 'fb':case 'facebook':
				return $sort ? 'fb':'facebook';
			case 'ig':case 'insta':case 'instagram':
				return $sort ? 'ig':'instagram';
			case 'tw':case 'twitter':
				return $sort ? 'tw':'twitter';
			case 'gh':case 'github':
				return $sort ? 'gh':'github';
			case 'bb':case 'bitbucket':
				return $sort ? 'bb':'bitbucket';
			case 'sk':case 'slack':
				return $sort ? 'sk':'slack';
			case 'yt':case 'youtube':
				return $sort ? 'yt':'youtube';
			case 'vm':case 'vimeo':
				return $sort ? 'vm':'vimeo';
			case 'dm':case 'dailymotion':
				return $sort ? 'dm':'dailymotion';
			case 'pt':case 'patreon':
				return $sort ? 'pt':'patreon';
			case 'gv':case 'gravatar':
				return $sort ? 'gv':'gravatar';
			case 'tl':case 'telegram':
				return $sort ? 'tl':'telegram';
			default:
				throw new NCException('Social name label not found.');
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
	static function __callStatic($label, $args){
		if($label == 'domain'){
			self::$lastResult = gethostbyname($args[0]) != $args[0];
			return __CLASS__;
		}
		$label = self::normalize_label($label);
		switch(count($args)){
			case 2:
				self::find($label, $args[0], trim($args[1]));
				break;
			case 1:
				$medias = self::medias();
				self::find($label, count($medias[$label]) == 1 ? array_keys($medias[$label])[0] : self::is_email($args[0]) ? 'email' : 'id', trim($args[0]));
				break;
			default:
				throw new NCException('No parameters for searching a '.$label.' account.');
		}
	}

	/**
	 * Method verbose. Used when you need that return:
	 * TRUE		= founded / exist
	 * FALSE	= not found / don't exist
	 * NULL		= invalid ID or equivalent
	 *
	 * @return bool
	 */
	static function isThere(){
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
	static function isAvailable(){
		return self::$lastResult === null ? null : !self::$lastResult;
	}

	/**
	 * Basically, check if the arguments are valid, look in the respective media method and validate the return value.
	 *
	 * @param array $args 
	 * @throws NCException when search method not supported or invalid params 
	 * @return void
	 */
	static function find(...$args){
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
						#new NCException('Invalid '.self::normalize_label($label,false).' '.($method == 'phone' ? 'phone number':$method).'.');
						return self::$lastResult = null;
						
					}
				}else{
					throw new NCException('Search method not supported.');
				}

				if (!isset($medias[$label][$method]['check']))
					throw new NCException('Search parameter "check" has not been declared.');
				if (!is_callable($medias[$label][$method]['check']))
					throw new NCException('Search parameter "check" must be an anonymous function.');

				return self::$lastResult = $medias[$label][$method]['check'](self::curl($options));
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
	static function parse_endpoint($endpoint,$values=[]){
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
	static function is_phone($number=0){
		$c = preg_replace("/[^0-9]/",'',$number);
		return $c && strlen($c)>9 && preg_match("/^[1-9]{2}9?[0-9]{8}$/",$c) ? true : false;
	}

	/**
	 * Verify e-mail is valid.
	 *
	 * @param string $email
	 * @return bool 
	 */
	static function is_email($email=''){
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU',$email) ? true : false;
	}

	/**
	 * Navigates the endpoints and returns the result.
	 *
	 * @param array $options 
	 * @throws NCException when $options is invalid
	 * @return mixed 
	 */
	static function curl($options=[]){
		#var_dump($options);die;
		if (!isset($options['url']))
			throw new NCException('CURL options need URL parameter.');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $options['url']);
		if (isset($options['header']) && $options['header'])
			curl_setopt($ch, CURLOPT_HEADER, 1);
		if (isset($options['headers']))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIESESSION, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
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