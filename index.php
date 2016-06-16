<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8"/>
<title>Crunchy RSS</title>
<link href="style.css?1" rel="stylesheet"/>
</head>

<body>
<div id="content">

<?

	$json = file_get_contents("data/collection.json");
	$data = json_decode($json,true);

?>

<table class="main" id="letters">
<tr>
<td>
<?
	
	$preg_string = '/^[a-z]$/';
	$preg_num    = '/^num$/';
	$dev_edition = false;
	
	$id = isset($_GET['id']) ? strtolower($_GET['id']) : false;
	
	if($dev_edition){
		$letter = $id && ( preg_match($preg_string,$id) || preg_match($preg_num,$id) ) ? $id : 'all';
		print '<a class="lett'.($letter=='all'?' lett_sel':'').'" href=".">All</a>';
	}
	else{
		$letter = $id && ( preg_match($preg_string,$id) || preg_match($preg_num,$id) ) ? $id : 'num';
	}
	
	print '<a class="lett'.($letter=='num'?' lett_sel':'').'" href="?id=num">#</a>';
	foreach (range('a', 'z') as $char) {
		if($char==$letter){
			print '<span class="lett lett_sel">'.strtoupper($char).'</span>';
		}
		else{
			print '<a class="lett" href="?id='.$char.'">'.strtoupper($char).'</a>';
		}
	}

?>
</td>
</tr>
</table>

<table class="main" id="list">
	
	<tr>
		<td>Title</td>
		<td>HTML</td>
		<td>RSS</td>
	</tr>
	<tr>
		<td><a href="show.php?p=latest"><b>Latest Crunchyroll Anime Videos</b></a></td>
		<td><a href="http://feeds.feedburner.com/crunchyroll/rss/anime?format=html" target="_blank">Show</a></td>
		<td><a href="http://feeds.feedburner.com/crunchyroll/rss/anime?format=xml" target="_blank">Show</a></td>
	</tr>
	<?
	
	$data_strs = [];
	
	foreach ($data as $sid => $title){
		if($letter == 'all' || strtolower($title[0]) == $letter || $letter == 'num' && !in_array(strtolower($title[0]), range('a','z')) ){
			$data_strs[] = $title.'|||'.$sid;
		}
	}
	
	natcasesort($data_strs);
	//var_dump($data_strs);
	
	foreach ($data_strs as $strdata){
		$strdata = explode('|||',$strdata);
		print'<tr>';
		print'<td><a href="show.php?id=' . $strdata[1] . '">' . $strdata[0] . '</a></td>';
		print'<td><a href="http://www.crunchyroll.com/showseriesmedia?id=' .  $strdata[1] . '" target="_blank">Show</a></td>';
		print'<td><a href="http://www.crunchyroll.com/syndication/feed?type=episodes&amp;id=' .  $strdata[1] . '" target="_blank">Show</a></td>';
		print'</tr>';
	}
	
	?>

</table>
</div>
</body>
</html>