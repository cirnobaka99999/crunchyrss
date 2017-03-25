<?

	require_once('data/functions.php');

	$prefix_rss = 'http://www.crunchyroll.com/syndication/feed?type=episodes&';
	$prefix_htm = 'http://www.crunchyroll.com/showseriesmedia?id=';
	$latest_rss = 'http://feeds.feedburner.com/crunchyroll/rss/anime';
	
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
	$json['image'] = $thispage == "p=latest" ? '' : $data['image']['url'];
	
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
	
	print'
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8"/>
			<title>'.$json['title'].'</title>
			<link href="style.css?1" rel="stylesheet"/>
			<script src="data/functions.js"></script>
		</head>
		<body>
			<div id="content" class="showphp">
			<div class="headrs">
				<h1><a href="show.php?'.$thispage.'">'.$json['title'].'</a></h1>
				'.$json['link2_html'].'
				<h4><a href="'.$json['link'].'" target="_blank">'.$json['link'].'</a></h4>
				'.($json['image']!=''?'<div><img id="showpic" src="'.$json['image'].'" alt=""/></div>':'').'
			</div>
	';
	
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
				<span>[</span><a href="./">back to list</a><span>]</span>
			</div>
			</div>
		</body>
	</html>
	';
	
		
?>