<?php
/* bjzhush@gmail.com  2012/09/01 
 * Read a folder of songs, random play, and recommended a number of first for on-demand
 * If the total number of songs is less than the number of random songs is recommended total song number recommended songs do not repeat
 * File exists judge, such as a song file does not exist you will be prompted
 * Casually write, there is no support for subdirectories, use is_file exclude subdirectories
 *
 */
$fpath = 'music';
$rand_num = 9;
// Check the referer, prohibit the same continuous play all songs)
if(isset($_SERVER['QUERY_STRING'])&&strlen($_SERVER['QUERY_STRING'])&&(!strpos($_SERVER['HTTP_REFERER'],$_SERVER['QUERY_STRING'])===FALSE))  {
  	unset($_GET['song']);
}


if ($handle = opendir($fpath)) {
	
	$all = array();
	while (false !== ($file = readdir($handle))) {
		if(!($file==='.'||$file==='..')){
		array_push($all,$file);
		}
		    }
	closedir($handle);
}

if(isset($_GET['song'])&&strlen($_GET['song'])){
$thissong = urldecode($_GET['song']);
}
else{
$thissong = $all[rand(0,count($all)-1)];

}


if(!file_exists(rtrim($fpath).'/'.$thissong)){
	$alertmsg = "File ".$thissong." Seems doesnot exist";
}
else{
	$alertmsg = '';
}

$arr_may = array();
$real_randnum = $rand_num>count($all) ? count($all) : $rand_num;
while(count($arr_may)<$real_randnum){
	$tmp_one = $all[rand(0,count($all)-1)];
	in_array($tmp_one,$arr_may)? NULL : array_push($arr_may,$tmp_one);
}


echo "<html>
	<head>
	<title>".$thissong."--My Music Box</title>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>
	<script type='text/javascript'>
$(document).keyup(function(e){
	var key =  e.which;
	if(key == 32){
		var song = $('#media').get(0);
		if(song.paused)
		{
			song.play();
}
else
{
	song.pause();

}
					}
	});

  </script>
	</head>
	<body>
	<br> <br> <br> <br> <br> <br>
	";
echo '<div align="center">Now Playing：'.$thissong;
echo "<br><br>";

echo $alertmsg;

echo '<a href="'.$_SERVER['SCRIPT_NAME'].'"><img src=./previous.jpg></a><br><br><br><br>';

echo ' <audio onended="document.location.reload()" id="media" controls="controls" autoplay="autoplay">
	<source src="music/'.$thissong.'" type="audio/mpeg" />
	Your browser does not support the audio element.
	</audio>
	';
echo '<a href="'.$_SERVER['SCRIPT_NAME'].'"><img src=./next.jpg></a><br><br><br><br>';
echo "Perhaps also can listen to:<br><br><br>";

foreach($arr_may as $k =>$song){
	echo '<a href="'.$_SERVER['SCRIPT_NAME'].'?song='.urlencode($song).'">'.$song.'</a><br><br>';

}


echo "	@2012 <a href='http://www.glitsolutions.inc.ug' target='_blank'>GLIT Solutions</a> </div>

	</body>
	</html>
	";
