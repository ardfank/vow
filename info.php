<?php
function hf($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
if (isset($_GET['url'])){
	$vurl=$_GET['url'];
	$res = shell_exec("/usr/bin/yt-dlp -j -S '+size,+br' '$vurl'");
	$re = json_decode($res);
	$wr = $re->->webpage_url;
	if (isset($re->formats)){
		$cf=count($re->formats)-1;
		$is = (isset($re->url))?$re->url:$re->formats[$cf]->url;
		$si='';
		foreach($re->formats as $fo){
			$nm=(isset($fo->format_note))?$fo->format_note:$fo->format;
			$ur=$fo->url;
			$si.="<li><a download href='$ur'>$nm (".hf($fo->filesize)." ; ".$fo->resolution.")</a></li>";
		}
		$isi = "<div style='width:90%;position: absolute'><div style='position: relative;float:left;max-width:65%;height:80vh'><video style='width:100%' src='$is' loop muted controls></video></div><div style='left:10px;position:relative'><a href='$wr'>$wr</a><li>$si</li></div></div>";
	}else{$isi = "Salah format";}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WoW</title>
<style>
body{background-color:#000;background-image:linear-gradient(15deg, #000 81%, #f80 90%,#fff 92%,#000 95%);background-attachment:fixed;height:900px;color:#fda;font: medium calibri;}
a{color:#fda;text-decoration: none;}
</style>
<script type='text/javascript' src='jquery.min.js'></script>
</head>
<body>
<input type="text" name="stn" id="stn" placeholder="Ganti URL"/>
<?=$isi?>
<script>
$(document).ready(function(){
	 $('#stn').change(function(){
		var imgs=$(this).val();
		window.location.href = '/info.php?url='+imgs;
	});
});
</script>
</body>
</html> 
