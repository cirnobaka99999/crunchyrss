<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8"/>
<title>Crunchy RSS</title>
<style>
body{margin:0 auto;width:861px;}
table{table-layout:fixed;margin:0 auto;border:1px;}
table#season{text-align:center;width:100%;}
tr td:first-child{min-width:800px;}
td,th{padding:5px;}
a{color:black;text-decoration:none;}
#list a:hover{text-decoration:underline;}
#season a:hover, #list tr:hover, #list tr:hover a{color:#99c;}
.back{margin-bottom:25px;text-align:center;}
.lett{
	background-color: #f8f8f8;
	border: 1px solid #ccc;
	margin-right: 5px;
	padding: 5px;
}
.lett:last-child{margin-right:0;}
span.lett{color:#99c;}
#list td{text-align:center;}
#list td:first-child{text-align:left;}

</style>
</head>

<body>

<?

	$json = file_get_contents("data/collection.json");
	$data = json_decode($json,true);

?>

<table id="season">
<tr>
<td>
<?
	
	$preg_string = '/^[a-z]$/';
	$preg_num    = '/^0-9$/';
	
	$id = isset($_GET['id']) ? strtolower($_GET['id']) : false;
	$letter = $id && ( preg_match($preg_string,$id) || preg_match($preg_num,$id) ) ? $id : 'all';
	
	print '<a class="lett lett_all" href=".">All</a>';
	print '<a class="lett lett_num" href="?id=0-9">#</a>';
	foreach (range('a', 'z') as $char) {
		if($char==$letter){
			print '<span class="lett lett_'.$char.'">'.strtoupper($char).'</span>';
		}
		else{
			print '<a class="lett lett_'.$char.'" href="?id='.$char.'">'.strtoupper($char).'</a>';
		}
	}

?>
</td>
</tr>
</table>

<style><?

if($letter=='0-9'){
	print'.lett_num{font-weight:bold;}';
}
else{
	print'.lett_'.$letter.'{font-weight:bold;}';
}

?></style>

<table id="list">
	
	<tr>
		<th>Title</th>
		<th>HTML</th>
		<th>RSS</th>
	</tr>
	<tr>
		<td><a href="show.php?p=latest"><b>Latest Crunchyroll Anime Videos</b></a></td>
		<td></td>
		<td style="text-align:center;"><a href="http://www.crunchyroll.com/rss/anime" target="_blank">Show</a></tr>
	</tr>
	<?
	
	$data_strs = [];
	
	foreach ($data as $sid => $title){
		if($letter == 'all' || strtolower($title[0]) == $letter || $letter == '0-9' && !in_array(strtolower($title[0]), range('a','z')) ){
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
</body>
</html>