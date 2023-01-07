<?php
function hf($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
$PATH=dirname(__FILE__)."/video";
if (isset($_GET['up'])){
	$vurl=$_GET['up'];
	$vname=time().'.mp4';
	echo $vurl;
	if(strpos($vurl,"tiktok.com")!==false){
		shell_exec("/usr/bin/yt-dlp -S '+size,+br' -o '$PATH/$vname' '$vurl' | /usr/bin/logger -t MOV");		
	}else{
		shell_exec("/usr/bin/yt-dlp -o '$PATH/$vname' '$vurl' | /usr/bin/logger -t MOV");
	}
	shell_exec(dirname(__FILE__)."/sh/thumb.sh '$PATH/$vname' | logger -t MOV");
	shell_exec(dirname(__FILE__)."/sh/scale.sh '$PATH/$vname' | logger -t MOV > /dev/null 2>/dev/null &");
	header('Location: https://vow.sa.ya/?'.$vname);
}
if (isset($_GET['url'])){
	$vurl=$_GET['url'];
	$agent = (isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:"";
	if (strlen(strstr($agent, 'Firefox')) > 0 && strpos($vurl,"tiktok.com")!==false) {
		$res = shell_exec("/usr/bin/yt-dlp -j -S '+br' '$vurl'");
	}else{
		$res = shell_exec("/usr/bin/yt-dlp -j -S '+size,+br' '$vurl'");
	}
	$re = json_decode($res);
	$wr = $re->webpage_url;
	if (isset($re->formats)){
		$cf=count($re->formats)-1;
		$is = (isset($re->url))?$re->url:$re->formats[$cf]->url;
		$si='';
		foreach($re->formats as $fo){
			$nm=(isset($fo->format_note))?$fo->format_note:$fo->format;
			$fs=(isset($fo->filesize))?(int)$fo->filesize:$fo->filesize_approx;
			$ur=$fo->url;
			$si.="<li><a href='$ur' download>$nm (".hf($fs)." ; ".$fo->resolution.")</a></li>";
		}
		$isi = "<div style='width:99%;position: absolute'><div style='position: relative;float:left;width:60%;height:80vh'><video style='width:100%' src='$is' loop muted controls></video></div><div style='left:10px;position:relative;float:left;width:30%;word-wrap:break-word'><a href='$wr'>$wr</a><ol>$si</ol></div></div><a href='https://vow.sa.ya/info.php?up=$wr'>UP</a>";
	}else{$isi = "$wr<br/>$vurl<br/>Video tidak ditemukan, not found, 404, dll, etc";}
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
video{
	height: 100%;
	width: 99%;
	object-fit: contain;
}
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
		window.location.href = 'info.php?url='+imgs;
	});
});
</script>
</body>
</html> 
