<?

	require_once('data/functions.php');

	$prefix_rss = 'http://www.crunchyroll.com/syndication/feed?type=episodes&';
	$prefix_htm = 'http://www.crunchyroll.com/showseriesmedia?id=';
	$latest_rss = 'http://www.crunchyroll.com/rss/anime';
	
	$json = array();
	
	function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}
	
	if(isset($_GET['id']) && preg_match('/[1-9]\d*/',$_GET['id']) && get_http_response_code($prefix_rss."id=".$_GET['id']) == "200"){
		$thispage = "id=".$_GET['id'];
		$xml_uri = $prefix_rss.$thispage;
	}
	else if(isset($_GET['group_id']) && preg_match('/[1-9]\d*/',$_GET['group_id']) && get_http_response_code($prefix_rss."group_id=".$_GET['group_id']) == "200"){
		$thispage = "group_id=".$_GET['group_id'];
		$xml_uri = $prefix_rss.$thispage;
	}
	else if(isset($_GET['p']) && $_GET['p'] == 'latest' && get_http_response_code($latest_rss) == "200" ){
		$thispage = "p=latest";
		$xml_uri = $latest_rss;
	}
	else{
		print'wrong request<br/><a href="./">back to list</a>';
		die();
	}
	
	// build data
	$data = json_decode(json_encode(simplexml_load_string(file_get_contents($xml_uri),'Feed')),true)['channel'];
	
	// insert data
	$json['title'] = preg_replace('/ Episodes$/', '', $data['title']);
	$json['link'] = $xml_uri == $latest_rss ? $latest_rss : $data['link'];
	$json['image'] = $data['image']['url']; // <img src="'.$data['image']['url'].'" alt=""/>
	
	if( isset($data['item']['mediaId']) ){
		$data['item'] = [$data['item']];
	}
	
	// items
	$items = $data['item'];
	$json['count'] = count($items);
	$i = -1;
	
	$json['items'] = array();
	
	if(count($items) > 0){
		foreach ($items as $item){
			$i++;
			// var_dump($item["duration"]); // ["episodeTitle"]["episodeNumber"]["seriesTitle"]
			$json['items'][$i]['id'] = $item['mediaId'];
			$json['items'][$i]['thumb'] = $item['enclosure']['@attributes']['url'] ?
				$item['enclosure']['@attributes']['url'] : 'http://static.ak.crunchyroll.com/i/coming_soon_beta_thumb.jpg';
			$json['items'][$i]['duration'] = $item["duration"] ? $item["duration"] : 0;
			$json['items'][$i]['title'] = $item["title"];
			$json['items'][$i]['air_date'] = date('Y-m-d H:i:s T',strtotime($item['pubDate']));
			if($item["subtitleLanguages"]){
				$json['items'][$i]['subtitles'] = array_map("sub2flag",explode(",",$item["subtitleLanguages"]));
			}
			$json['items'][$i]['countries'] = array_map("cc2cn",explode(" ",mb_strtoupper($item["restriction"]["@text"])));
		}
	}
	
	if(isset($_GET['r']) && $_GET['r'] == 1){
		$json['items'] = array_reverse($json['items']);
	}
	$json['link'] = str_replace("&", "&amp;", $json['link']);
	$json['link2'] = preg_match('/id=(\d)+/',$thispage) ? 'http://www.crunchyroll.com/showseriesmedia?'.$thispage : false;
	$json['link2_html'] = $json['link2'] ? '<h4><a href="'.$json['link2'].'" target="_blank">'.$json['link2'].'</a></h4>' : '';
	// var_dump($json['link2']);
	
	print'
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8"/>
			<title>'.$json['title'].'</title>
			<style>
				body{margin:0 auto;text-align:center;max-width:900px}
				h1,h4{margin:0;}
				a{color:black;text-decoration:none;}
				a:hover{text-decoration:underline;}
				img{float: left;vertical-align: top;}
				#showpic{margin: 5px 0;float:none;max-width:560px;}
				.episode_block{text-align:left;overflow:hidden;margin-bottom:5px;padding:5px;}
				.episode_block:hover{background-color:#99c;}
				.episode_img{float:left;height:90px;width:160px;position:relative;}
				.episode_img .video_time{position:absolute;bottom:5px;right:5px;padding:2px 5px;background:rgba(0,0,0,0.45);color:white;border-radius:2px;z-index:10;}
				.episode_title, .episode_data{margin-left:165px;}
				.countries{text-align:justify}
				.countries b:hover{text-decoration:underline;}
				.countries .countries_names{display:none}
				.countries .countries_names span{white-space:nowrap;}
				.countries .countries_names span:hover{font-style:italic;}
				.back{margin-bottom:25px;}
				.media_id{float:right;padding:0 0 5px 5px;float:right}
				.media_id input{text-align:center;}
				.flag{float:none;vertical-align:top;display:inline-block;height:20px;} 
			</style>
			<script>
			function shcn(el){
				var sel = el.parentNode.getElementsByClassName("countries_names")[0];
				switch(sel.style.display){
					case"none": sel.style.display = "inline"; break;
					case"inline": sel.style.display = "none"; break;
					default: sel.style.display = "inline";
				}
			}
			</script>
		</head>
		<body>
			<div>
				<h1><a href="show.php?'.$thispage.'">'.$json['title'].'</a></h1>
				'.$json['link2_html'].'
				<h4><a href="'.$json['link'].'" target="_blank">'.$json['link'].'</a></h4>
				<div><img id="showpic" src="'.$json['image'].'" alt=""/></div>
			</div>
	';
	
	// {"config_url":"http://www.crunchyroll.com/xml/?req=RpcApiVideoPlayer_GetStandardConfig&media_id=535080&video_format=0&video_quality=60&auto_play=0"}
	// http://www.crunchyroll.com/ajax/?req=RpcApiMedia_GetCollectionCarouselPage&collection_id=20461
	// http://www.crunchyroll.com/ajax/?group_id=264533&req=RpcApiUserQueue_Add
	// http://www.crunchyroll.com/ajax/?group_id=264533&req=RpcApiUserQueue_Delete
	// http://www.crunchyroll.com/affiliates_embed?coll_id=18126
	// http://www.crunchyroll.com/syndication/feed?type=episodes&id=18126&width=720&height=480&affiliate_code=af-53318-czoc
	// http://www.crunchyroll.com/affiliates_embed?coll_id=18126&media_id=535080
	// http://www.crunchyroll.com/ajax/?req=RpcApiMedia_GetEmbedCode&media_id=535080
	// <iframe src="http://www.crunchyroll.com/affiliate_iframeplayer?aff=af-53318-czoc&media_id=535080&video_format=0&video_quality=0&auto_play=0" width="720" height="480"></iframe>
	/*implode(", ", $item['countries'])*/ //duration
	
	function convertNum($num) {
		$num = $num < 10 ? '0' . $num : $num; return $num;
	}
	
	function convertTime($time) {
		
		$hours = floor( $time / 3600 );
		$hours_minutes = $hours > 0 ? $hours . ':' . convertNum(( $time / 60 % 60 )) : ( $time / 60 % 60);
		$seconds = ':' . convertNum(( $time % 60 ));
		
		return $hours_minutes.$seconds;
		
	}
	
	foreach($json['items'] as $item){
		print'
			<div class="episode_block">
				<div class="media_id">
					<span><b>Media ID:</b></span>
					<input value="'.$item['id'].'" readonly="readonly" size="4"/>
				</div>
				<div class="episode_img" style="background: url(\''.$item['thumb'].'\');">
					'.($item['duration']>0?'<span class="video_time">'.convertTime($item['duration']).'</span>':'').'
				</div>
				<div class="episode_title">
					<a href="http://www.crunchyroll.com/media-'.$item['id'].'" target="_blank">
						<b>'.$item['title'].'</b>
					</a>
				</div>
				<div class="episode_data">
					<div><b>AirDate:</b> '.$item['air_date'].'</div>
					'.($item['subtitles'] ? '<div><b>Subtitles:</b> '.implode(" ", $item['subtitles']).'</div>' : '').'
					<div class="countries"><b title="click to view/hide" onclick="shcn(this);">Countries:</b> <span class="countries_names"><span>"'.implode('"</span>, <span>"', $item['countries']).'"</span></span></div>
				</div>
			</div>
		';
	}
	
	print'
			<div class="back">
				<span>[</span>
				<a href="./">back to list</a>
				<span>]</span>
			</div>
		</body>
	</html>
	';
	
		
?>