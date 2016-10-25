<?php

if(isset($_POST['addlog']) && isset($_POST['fname'])){
	$filename = $_POST['fname'];
	
	file_put_contents('logs/'.$filename, $_POST['addlog']);
	header('Location: ?file='.$filename);
	exit();
}

if(isset($_POST['deletefile'])){
	$filename = $_POST['deletefile'];
	unlink('logs/'.$filename);
	header('Location: ?');
	exit();
}


?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>

<?php

$fname = 'log_'.date('Ymd_His', time()).'.log';
echo "
	  <button href='javascript:void(0);' onclick='addtext.style.display=\"block\"'>Add Log</button>

<form method='POST' id='addtext' style='display: none'>
	<input type=text name='fname' size=50 value='".$fname."'/><br>
	<textarea name=addlog cols=50 rows=15></textarea><br><br>
	<input type=submit value='save'/>
	<input type=button href='javascript:void(0);' onclick='addtext.style.display=\"none\"' value='Cancel'/>
</form>
<hr>";

$files  = scandir("logs/");

foreach($files as $i => $fname){
	if(preg_match("/.*\\.log/i", $fname)) {
		echo "<button onclick='window.location.href=\"?file=".$fname."\"'>".$fname."</button>";
	}
}

echo "<hr> ";

echo "
	<style>
		p { margin: 0px; white-space: pre; }
		p.unknown { color: black; }
		p.weblog { color: lightblue; }
		p.error { color: red; }
		p.mediaplayersdk { color: gray; margin-left: 50px; }
		p.playerevents { color: black; font-weight: bold; margin-left: 25px; }
	</style>
";

function getClass($line){
	if(preg_match("/\\[LOG\\].*/i", $line)) {
		return "weblog";
	}
	
	if(preg_match("/\\d+:\\d+:\\d+:(player\\.c|ffmpeg_content_provider\\.c|content_provider_thread\\.c|video_renderer_thread\\.c|egl_video_renderer_provider\\.c|video_decoder_thread\\.c|recorder_provider_thread\\.c).*/i", $line)){
		return "mediaplayersdk";
	}
	
	if(preg_match("/^(PLP_|CP_).*/i", $line)){
		return "playerevents";
	}
	
	if(preg_match("/^\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\.\\d+ \\d+-\\d+\\/[a-zA-Z.]+ E\\/.*/i", $line)){
	       return "error";
	}

	return "unknown";
}

if(isset($_GET['file'])){
	$filename = $_GET['file'];
	$handle = fopen('logs/'.$filename, "r");
	if ($handle) {
		echo "<h1>".$filename;
		echo "  <form method='POST' style='display: inline-block' ><input type=hidden name='deletefile' value='".$filename."'/><input type=submit value='delete log'/></form></h1>";
		
		while (($line = fgets($handle)) !== false) {
			echo "<p class='".getClass($line)."'>".$line."</p>";
		}
		fclose($handle);
	} else {
		echo "Could not open file: ".$filename."<br>";
	} 
}
