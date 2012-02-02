<?php
//タイムアウトをしないように設定
set_time_limit(0);

/**
 * ハッシュタグに#nicovideoが設定されているツイートだけ表示
 * usernameとpasswordは適切に設定してください
 **/
tweetStream('username', 'password', '#nicovideo');

/**
 * @param string $user		Twitterのログイン名
 * @param string $password	Twitterのパスワード
 * @param string $keyword	検索キーワード 
 **/
function tweetStream($user, $password, $keyword){
	$keyword = urlencode($keyword);
	$url = "https://{$user}:{$password}@stream.twitter.com/1/statuses/filter.json?track={$keyword}";

	if($stream = fopen($url, 'r')){
		while(!feof($stream)){
			//tweet data
			$tweet= json_decode(fgets($stream), true);
			//user name
			$user = $tweet['user']['screen_name'];
			//user image
			$userImage = $tweet['user']['profile_image_url'];
			//tweet text
			$text = $tweet['text'];

			if(!empty($user)){
				$timeline =  '<img src="'.$userImage.'">'.' <strong>'.$user.'</strong> : '.makeLink($text).PHP_EOL;
				echo $timeline;
				ob_flush();
				flush();
				sleep(1);
			}
		}
		fclose($stream);
	}
}

/**
 * リンク作成
 * @param string The url string
 * @return string
 **/
function makeLink($string){
	/*** make sure there is an http:// on all URLs ***/
	$string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
	/*** make all URLs links ***/
	$string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</A>",$string);
	/*** make all emails hot links ***/
	$string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<A HREF=\"mailto:$1\">$1</A>",$string);

	return $string;
}
