<?php
/* if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off" || $_SERVER['SERVER_PORT'] === "80") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
	echo $location;
    exit;
} */
$FOLDER=(isset($_GET['rt']))?$_GET['rt']:'movie';
$PATH=dirname(__FILE__)."/$FOLDER";
if(isset($_POST['rem'])){
	unlink($_POST['rem']);
	unlink($_POST['rem'].".gif");
}

if (isset($_POST['url']) && isset($_POST['name'])){
	$vurl=$_POST['url'];
	$vname=$_POST['name'];
	if(strpos($vurl,"tiktok.com")!==false){
		shell_exec("/usr/bin/yt-dlp -S '+size,+br' -o '$PATH/$vname' '$vurl' | /usr/bin/logger -t MOV");		
	}else{
		shell_exec("/usr/bin/yt-dlp -o '$PATH/$vname' '$vurl' | /usr/bin/logger -t MOV");
	}
	shell_exec("/usr/bin/ffmpeg -ss 3 -t 6 -i '$PATH/$vname' -filter_complex '[0:v] fps=9,scale=w=72:h=-2,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1' -loop 0 -f webp '$PATH/$vname.gif'");
	// shell_exec("/usr/bin/ffmpeg -y -i '$PATH/$vname' -vf 'scale=-2:760' -movflags faststart -preset veryfast -crf 24 -c:v libx264 -c:a copy '$PATH/$vname-hs.mp4'");
	// shell_exec("/usr/bin/mv -f '$PATH/$vname-hs.mp4' '$PATH/$vname'");
	shell_exec(dirname(__FILE__)."/sh/scale.sh '$PATH/$vname' | logger -t MOV > /dev/null 2>/dev/null &");
}
if( isset($_FILES['file'] ) ) {
	$st = $PATH."/".$_FILES['file']['name'];
	if (move_uploaded_file($_FILES['file']['tmp_name'], $st)) {
		shell_exec("/usr/bin/ffmpeg -ss 3 -t 6 -i '$st' -filter_complex '[0:v] fps=9,scale=w=72:h=-2,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1' -loop 0 -f webp '$st.gif'");
		// shell_exec("/usr/bin/ffmpeg -y -i '$st' -vf 'scale=-2:760' -movflags faststart -preset veryfast -crf 24 -c:v libx264 -c:a copy '$st-hs.mp4'");
		// shell_exec("/usr/bin/mv -f '$st-hs.mp4' '$st'");
		shell_exec(dirname(__FILE__)."/sh/scale.sh '$st' | logger -t MOV > /dev/null 2>/dev/null &");
	}
}

$img='';
$fli=array_reverse(glob("$FOLDER/*.{mp4,part,png,jpg,jpeg}",GLOB_BRACE),false);
$ran='?r';
if(isset($_GET['r'])){shuffle($fli);$ran='?';}
foreach ($fli as $ind => $file) {
	$filen= pathinfo($file);
    $img .= "<div class='responsive' alt='$file' title='".$filen['filename']."'><div style='left:10px;position: absolute;top: 5px;z-index:888;'>".$filen['filename']."</div><video preload='none' class='wow' src='$file' index='$ind' width='100%'></video></div>";
}

$icon = array();
$icon["del"]="data:image/png;base64,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";
$icon["up"]="data:image/png;base64,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";
$icon["ss"]="data:image/png;base64,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";
$icon["ran"]="data:image/png;base64,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";
$icon["next"]="data:image/png;base64,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";
$icon["prev"]="data:image/png;base64,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";
$icon["crop"]="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAABUZJREFUWEedl3tM21UUx8+P8mjpClQLY7wKhUphTLcpQxtKzMiGWQxYNtQAw4VH+ENMMAGiQTJYFlHAjD9Fus1Utrktwha2OHEYDYYFmTxmzAoFhLYUR2EwYRb6uoYfbfm92v1q/yDl3nPu93PPOffcWwx8/KDl5ZDlb648Wb/TD7YFI/gF84CbuhcCXpc3hZ8savRxOcDYOJi12kZ9xpHTWAiPjTnwXk2HmG/VrNb2arR665bEVFU7zUqVwcg/KhoSBu961fA4Ob3vEHKsrXnUdqyagBMtBrT4GBDHDzBuoEfb3TeuJIfs3z/JZMAIoI2TIfCjTFltEPH1l02hR7K95lmfX4Q2RkZoWs831l977lTxO9QJGgCTuEQzWsnhcr/yJRUzh7KQfdFEctnd9ml3yAnlceIgCWAyPB5hfHKhcQ++qIvtvir2Rdxl+8/gYOmjwrLzRF/x2L2XA8PC3CFyA5iam2+vdqiPMQkFvZRmjLt5PZoyhyj/e6wnbXwKyVY6+9Bt6/5CMrI7ADh+pPWHV0xQ+GTp/wQCM6kuFq6ebbnkcg4+nAnRFzpxbfzP/Kky9O/Pg+7FXYRU8rFNMxQtG1lDpKSkwPj4OK4xlbgPIbuNpoFPEoX8w0WQMDzAHBkAcKTK4GMuqx4D3d3dJEOijqDgzb7I1tYczKzTZRmycn5xoRHzszXW29uLZB/UkXb90LIBuca/2ESCBECKAkIgndNgmC73bbT54I/txewOkOonSE4TExOZycnJA9r4FJKgzmqB7PnpLVt3gQ0MDIBCofB4yjZXVvJ1B+TfETeLaaOlCAL88bEgWTLE3bnBFF9chAoRmBjvEPd/767Wrq6u2OLiYj2BgN5nCCdiK9oYMS+i1rMqYcHxCobY4gB5eXlTbeOTScT5BZsVsgxTriF3RHp6ekCpVHoFCP2ktoEEINGMqjhcrkeAiIgI4PP50Ie4JMa/bVZQbEO4ATQaTbpMJrtP3YxWLEOAbXOFvV/xk08AW06uPFPT8chmhUzDFLEmmO8ZbwCiz86ohO8W0CKgVqtRSUkJTt3Z2RlbUVGB55kK4R8TAwm//khMBy2bxJQLm880kIowMOkFEN+9SSMfGhp6JSMjY9i5GmmX1GZlsllBvpMOrwB4EepPFKON+79vGzrPpocD7jpu1DAjaiQWlhYha32ZtpFNvT5fpzhKPobWlZWs2QNyj42IAOMRgCkdHGEYSEbvkTthrAwBxzlks4PUMLldjsQw+vEFkPjnbx57gdlsNvB4vBhqOjo6OsIPN7eTHgCcMCFIxgYZ23po2Xt9EQ0f5eCTxsoq9PSHfvdmqe3YOYFHoKSkxKBWq2kAriNITQcnRAiSB4OYNj4VEZomuDSYLx2GWmhpaUF1dXXA4/HAbreDxWKBmpoaaGtrw/mqq6uhvb0d/06DEInAvrRzlQuUuRB57vOd63jLafXS1dum+sadBwnlXlCpVGnl5eXOS8NDme4Mk/oL1ZrxQYKTR0kRBG7fC/jHsglS4wy7u5eBiXpEt0yo6aX36hgpAn8CBABEXj5fKZDLfXqUaqMTEASQW7awvvakqKK0i8jKuLvJcAnC+EHkPSEEu6oqm/bUfuj1WT6TrkB2E/3ptufyhWu75K89+1nuUp1VvIGs+jmPyUbrZgB/DsCGBTBBMO0NSXRM0owmY1wu+x8mLueNuTmJXnF0GvzID9RnlqDTgHswDWK7r3utIVYF9nRkJGo++615LJTPSlugPAaR575gtTYrI6rqfHVNIzxeO2016AELEQBvbxIISkubeImJPv88/w8xnxQbeN5WaQAAAABJRU5ErkJggg==";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WoW</title>
<style>
body{background-color:#000;background-image:linear-gradient(15deg, #000 81%, #f80 90%,#fff 92%,#000 95%);background-attachment:fixed;height:900px;color:#fda;font: medium calibri;}
* {
  box-sizing: border-box;
}
.responsive {
  overflow: hidden;
  display:none;
  float: left;
  position:relative;
  width: 19.3vw;
  height: 19.3vw;
  margin: 1px;
}

.responsive video{
	width: 100%;
	height: 100%;
	border:1px solid #f80;
	cursor: pointer;
}
#nav{
	top: 0px;
    position: absolute;
    width: 100%;
    cursor: pointer;
}
.icon{
	width: 10vw;
    height: 10vh;
    display: inline-block;
	opacity:0.1;
}

#prev,#next{
	background:rgba(9,9,9,0);
	position: absolute;
	top:0;
	width:10%;
	cursor: pointer;
	height:90%;
}
#next{
	right:0;
}
#prev>img,#next>img{
	opacity:0.1;
	object-fit: contain;
    width: 40%;
    height: 100%;
}
#prev>img:hover,#next>img:hover{
	opacity:0.9;
	transform: scale(1.5);
}
#prev:hover,#next:hover{
	background:rgba(235,245,255,0.1);
}
#light{
	display: none;
	background-image:linear-gradient(0deg, rgba(0,50,9,0.8) 23%, #f80 130%);
	top:0;
	bottom:0;
	left:0;
	right:0;
	position:fixed;
	text-align: center;
	z-index:999;
}
#light>video{
	height: 100%;
	/* width: 99%; */
	object-fit: contain;
}
#up{
	display: none;
	object-fit: contain;
	margin:0 auto;
	background:rgba(131, 51, 4, 0.8);
	top:100;
	left:50;
	position:fixed;
	border: 3px solid #a50;
	height: 20vw;
	width: 50vw;
	z-index:999;
}
.ptex {
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
  color: white;
  font-weight: bold;
  border: 3px solid #f1f1f1;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  width: 80%;
  padding: 20px;
  text-align: center;
}
.random{
	position: fixed;
    cursor: pointer;
    width: 48px;
	z-index:990;
}
.random a{
	opacity: 0.2;
    display: list-item;
    height: 48px;
}
.icon:hover,.random a:hover{
	opacity:0.9;
}
@media only screen and (max-width: 1380px) {
  .responsive {
	width: 23.7vw;
	height: 23.7vw;	
  }
}
@media only screen and (max-width: 900px) {
  .responsive {
	width: 47vw;
	height: 47vw;	
  }
  #up{
	height: 40vh;
	width: 90vw;
  }
}

@media only screen and (max-width: 700px) {
  .responsive {
	width: 94vw;
	height: 94vw;	
  }
  #up{
	height: 40vh;
	width: 90vw;
  }
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<script type='text/javascript' src='jquery.min.js'></script>
</head>
<body>
<div class='random'>
<a href='#' onclick='javascript:rr(event);' title='Random Video' style='background:url(<?=$icon['ran']?>)no-repeat center'></a>
<a href='#' onclick='javascript:ss();' title='Slideshow' style='background:url(<?=$icon['ss']?>)no-repeat center'></a>
<a href='#' onclick='javascript:up(event);' title='Upload Video'  style='background:url(<?=$icon['up']?>)no-repeat center'></a>
</div>
<div id='up' contenteditable='true' ondrop='drop(event)' ondragover='allowDrop(event)' >
<div class='ptex'>DRAG or PASTE YOUR PICTURE HERE</div>
<div style="display:none;z-index:999999;width:100%;height:100%;background:#000 url('loading.gif') no-repeat center;position:absolute;top:0;left:0;opacity:0.9" id='ads'></div>
<form action='#' name='upload' method='post' enctype='multipart/form-data'>
<label for="st" style="position:absolute;bottom:0;margin:5px;border:1px solid;background-image:linear-gradient(#222,#666,#222);padding: 5px;">Pilih File</label>
<input type="file" name="st" id="st" accept="video/mp4" style="display:none"/><input type="text" name="stn" id="stn" placeholder="Nama file"/>
</form>
</div>
<div id='light'>
<video id='wow' index=0 autoplay muted controls></video>
<div id='prev' onclick='javascript:prev(event);'><img src='<?=$icon['prev']?>'/></div>
<div id='next' onclick='javascript:next(event);'><img src='<?=$icon['next']?>'/></div>
<div id='nav'>
	<div title='Remove video file!' onclick='javascript:rem(event);' style='background:url(<?=$icon['crop']?>)no-repeat center' class='icon'></div>
	<div title='Previous Image' onclick='javascript:prev(event);' style='background:url(<?=$icon['prev']?>)no-repeat center' class='icon'></div>
	<div title='Play Slideshow' id='play' onclick='javascript:event.stopPropagation();ss();' style='background:url(<?=$icon['ss']?>)no-repeat center' class='icon'></div>
	<div title='Next Image' onclick='javascript:next(event);' style='background:url(<?=$icon['next']?>)no-repeat center' class='icon'></div>
	<div title='Close' id='close' style='background:url(<?=$icon['del']?>)no-repeat center' class='icon'></div>
</div>
</div><div id='gal'>
<?=$img?></div>
<script>
var im=<?php echo json_encode($fli);?>;
//ON VIEWPORT
function los(en,f){
	var ps = $(window).scrollTop() + $(window).height();
		at = en.offset().top;av = $(window).scrollTop();		
			if(at > av && at < ps){
				f();
			}
}

//SWIPE EVENT START
	document.getElementById("wow").addEventListener('touchstart', handleTouchStart, false);        
	document.getElementById("wow").addEventListener('touchmove', handleTouchMove, false);
	var xDown = null;                                                        
	var yDown = null;

	function getTouches(evt) {
	  return evt.touches ||             // browser API
			 evt.originalEvent.touches; // jQuery
	}                                                     
																			 
	function handleTouchStart(evt) {
		const firstTouch = getTouches(evt)[0];                                      
		xDown = firstTouch.clientX;                                      
		yDown = firstTouch.clientY;                                      
	};                                                
																			 
	function handleTouchMove(evt) {
		if ( ! xDown || ! yDown ) {
			return;
		}

		var xUp = evt.touches[0].clientX;                                    
		var yUp = evt.touches[0].clientY;

		var xDiff = xDown - xUp;
		var yDiff = yDown - yUp;
																			 
		if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
			if ( xDiff > 0 ) {
				/* right swipe */ 
				next(event);
			} else {
				/* left swipe */
				prev(event);
			}                       
		} else {
			if ( yDiff > 0 ) {
				/* up swipe */
				next(event);
				//event.stopPropagation();ss();
			} else { 
				/* down swipe */
				prev(event);
			}                                                                 
		}
		/* reset values */
		xDown = null;
		yDown = null;                                             
	};
//

function allowDrop(ev) {
  ev.preventDefault();
}
function drop(e) {
	$('#ads').show();
	e.preventDefault();
	e.stopPropagation();
	var items = e.dataTransfer.items;
	for( var i = 0, len = items.length; i < len; ++i ) {
		var item = items[i];
		// console.log(item);
		if( item.type.indexOf('video') === 0 ) {
			var stn=($('#stn').val()!='')?$('#stn').val():new Date().getTime();
			submitFileForm(item.getAsFile(),stn);
			return;
		}
	}
};
	function ss(){
		var e=$('#wow').attr('index');
		e=(e>=im.length||e=='')?0:e;
		$('#light').fadeIn(200);
		$('#light').css({'background':'linear-gradient(90deg,rgba(255,0,0,.3), rgba(0,0,0,1), rgba(0,0,255,.3)), url('+im[e]+'.gif) center','background-size':'contain'});
		$('#wow').attr({'src':im[e],'index':e,'loop':false}).on('ended',function(){
			e++;
			$('#light').css({'background':'linear-gradient(90deg,rgba(255,0,0,.3), rgba(0,0,0,1), rgba(0,0,255,.3)), url('+im[e]+'.gif) center','background-size':'contain'});
			e=(e>=im.length||e=='')?0:e;
			$('#wow').attr({'src':im[e],'index':e});
		});
		$('#wow').css('transform','translate(0px) scale(1)');
	}
	function rr(e){
		e.preventDefault();
		var href=window.location.href;
		// var href = window.location.href,queryString = 'r='+sessionStorage.getItem("myItem"),newHref;
		if(href.indexOf('#')!== -1){
			href = href.replace('#','');
		}
		if(href.indexOf('?r=r')!== -1){
			newHref = href.replace('?r=r','');
		}else if(href.indexOf('&r=r')!== -1){
			newHref = href.replace('&r=r','');
		}else if (href.indexOf('?') !== -1) {
			newHref = href + '&r=r';
		} else {
			newHref = href + '?r=r';
		}
		window.location.href = newHref;
		console.log(newHref);
	}
	function light(i){
		$('#light').hide(000);
		$('#light').fadeIn(200);
		$('#light').css({'background':'linear-gradient(90deg,rgba(255,0,0,.3), rgba(0,0,0,1), rgba(0,0,255,.3)), url('+im[i]+'.gif) center','background-size':'contain'});
		$('#wow').attr({'src':im[i],'index':i,'loop':true});
		$('#wow').css('transform','translate(0px) scale(1)');
	 }
	function next(e){
		e.stopPropagation();
		var l=parseInt($('#wow').attr('index'))+1;
		l=(l>=im.length)?0:l;
		$('#light').show(500);
		light(l);ss();
	}
	function prev(e){
		e.stopPropagation();
		var l=parseInt($('#wow').attr('index'))-1;
		l=(l<0)?im.length-1:l;
		$('#light').show(500);
		light(l);
	}
	function rem(e){
		e.stopPropagation();
		var l=$('#wow').attr('src');
		var r = confirm("Hapus file "+l+" ?");
		if (r == true) {
			$.post('',{rem:l}).done(function(){window.location.reload(true);});
		}
	}
	function up(e){
		e.preventDefault();
		$('#up').toggle(200);
	}
	function tut(){
		$('#light').fadeOut(500);
		$('#up').fadeOut(500);
		$('video').trigger('pause');
		document.removeEventListener('touchstart', handleTouchStart);        
		document.removeEventListener('touchmove', handleTouchMove);
	}

	function ScrollZoom(container,max_scale,factor){
		var target = container.children().first()
		var size = {w:target.width(),h:target.height()}
		var pos = {x:0,y:0}
		var zoom_target = {x:0,y:0}
		var zoom_point = {x:0,y:0}
		var scale = 1
		target.css('transform-origin','0 0')
		target.on("mousewheel DOMMouseScroll",scrolled)

		function scrolled(e){
			target.css('cursor','zoom-in')
			var offset = container.offset()
			zoom_point.x = e.pageX - offset.left
			zoom_point.y = e.pageY - offset.top

			e.preventDefault();
			var delta = e.delta || e.originalEvent.wheelDelta;
			if (delta === undefined) {
			  //we are on firefox
			  delta = e.originalEvent.detail;
			}
			delta = Math.max(-1,Math.min(1,delta)) // cap the delta to [-1,1] for cross browser consistency

			// determine the point on where the slide is zoomed in
			zoom_target.x = (zoom_point.x - pos.x)/scale
			zoom_target.y = (zoom_point.y - pos.y)/scale

			// apply zoom
			scale += delta*factor * scale
			scale = Math.max(1,Math.min(max_scale,scale))

			// calculate x and y based on zoom
			pos.x = -zoom_target.x * scale + zoom_point.x
			pos.y = -zoom_target.y * scale + zoom_point.y


			// Make sure the slide stays in its container area when zooming out
			if(pos.x>0)
				pos.x = 0
			if(pos.x+size.w*scale<size.w)
				pos.x = -size.w*(scale-1)
			if(pos.y>0)
				pos.y = 0
			 if(pos.y+size.h*scale<size.h)
				pos.y = -size.h*(scale-1)

			update()
		}

		function update(){
			target.css('transform','translate('+(pos.x)+'px,'+(pos.y)+'px) scale('+scale+','+scale+')')
		}
	}

	function submitFileForm(file,name) {
		var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();
		var formData = new FormData();
		var dt = name+'.mp4';
		formData.append('file', file, dt );
		formData.append('extension', extension );
		formData.append("mimetype", file.type );

		var xhr = new XMLHttpRequest();
		xhr.responseType = "blob";
		xhr.open('POST', "<?php echo $_SERVER['REQUEST_URI']; ?>");
		xhr.onload = function () {
			if (xhr.status == 200) {
				im.push("<?=$FOLDER?>\/"+dt);
				$('#gal').prepend("<div class='responsive' style='display:block' alt='"+dt+"' title='"+dt+"'><div style='left:10px;position: absolute;top: 5px;z-index:888;'>"+name+"</div><video poster='<?=$FOLDER?>/"+dt+".gif' class='wow' width='100%' src='<?=$FOLDER?>/"+dt+"' index='"+(im.length-1)+"' onclick='javascript:light("+(im.length-1)+");'></video></div>");
				$('#up,#ads').toggle(200);$('#stn').val('');
			}
		};
		xhr.send(formData);
	}	
 $(document).ready(function(){
	 $('.responsive').each(function(i,tem){
		if(i<17){
			$(this).fadeIn();
			var poster=$('.wow',this).attr('src')+'.gif';
			if ($('.wow',this).attr('poster')!=poster) {
				$('.wow',this).attr('poster',poster);
			}
		} 
	 });
	$(window).on('resize scroll', function() {
		$('.responsive').each(function(){
			var ene=$(this);
			los($('footer'),function(){			
				ene.fadeIn(1000);
				var poster=$('.wow',ene).attr('src')+'.gif';
				if ($('.wow',ene).attr('poster')!=poster) {
					$('.wow',ene).attr('poster',poster);
				}
			});
		});
	});
	 $('#st').change(function(){
		 $('#ads').show();
		var imgs=$(this).prop('files')[0];
		var stn=($('#stn').val()!='')?$('#stn').val():new Date().getTime();
		submitFileForm(imgs,stn);
		return;
	});
	document.onpaste = function (e) {
		if($('#up').is(':visible') && $('#ads').not(':visible')){
			$('#ads').show();
			e.preventDefault();
			e.stopPropagation();
			var items = e.clipboardData.items;
			for( var i = 0, len = items.length; i < len; ++i ) {
				var item = items[i];
				if( item.kind === "file" ) {
					var stn=($('#stn').val()!='')?$('#stn').val():new Date().getTime();
					submitFileForm(item.getAsFile(),stn);
					return;
				}
				if( item.kind === "string" && item.type==="text/plain") {
					var imgs = e.clipboardData.getData('Text');
					var dtx=($('#stn').val()!='')?$('#stn').val():new Date().getTime();
					var dt=dtx+'.mp4';
					$.post(window.location, {url: imgs,name: dt}, function() {
						im.push("<?=$FOLDER?>\/"+dt);
						$('#gal').prepend("<div class='responsive' style='display:block' alt='"+dt+"' title='"+dt+"'><div style='left:10px;position: absolute;top: 5px;z-index:888;'>"+dtx+"</div><video poster='<?=$FOLDER?>/"+dt+".gif' class='wow' width='100%' loading='lazy' src='<?=$FOLDER?>/"+dt+"' index='"+(im.length-1)+"' onclick='javascript:light("+(im.length-1)+");'></video></div>");
						$('#up,#ads').toggle(200);
					});
				}
			}
		}
	};
	$('#wow').on('mouseover',function(){
		new ScrollZoom($('#light'),10,0.1);
	});
	 document.onkeydown = function(e) {
		 if($('#wow,#up').is(':visible')){
			switch (e.keyCode) {
				case 37:
					prev(event);
					break;
				case 39:
					next(event);
					break;
				case 27:
					$('#light,#up').fadeOut(500);
					$('video').trigger('pause');
					break;
				}
			}
		}
	$('.wow').click(function(e){
		e.preventDefault();
		$('#up').fadeOut(500);
		var link = $(this).attr('index');
		light(link);
	});
	$('#close').click(tut);
	$('#light').on('click', function(e) {
	  if (e.target !== this)
		return;
		tut();
	});
});
</script>
<div class='clearfix'></div>
<hr/>
<footer style='text-align:center'>--ooOOoo--</footer>
</body>
</html> 
