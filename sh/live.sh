#! /bin/sh
killall mplayer
echo -n "Enter URL: " &&
read url
#rx=$(shuf -i 49-51 -n1)
#rxx=$(shuf -i 99-100 -n1)
#ry=$(shuf -i 49-51 -n1)
#find /media/d/vow/video/ -type f -not -path "*vid/*" -iname "*.mp4" > ~/list.m3u
#find /media/d/vow/video/ -type f -iname "*.mp4" > ~/list.m3u
#mplayer -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale -zoom -xy 420 -geometry $rxx%:$ry% > /dev/null 2>&1 &
#mplayer -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale -zoom -y 768 -geometry $rx%:$ry% > /dev/null 2>&1 &
#mplayer -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale -zoom -xy 428 -geometry $rx%:$ry% > /dev/null 2>&1 &

mplayer -v ffmpeg://$url -vo fbdev -vf scale=w=-2:h=760 -geometry 50%:50% > /dev/null 2>&1 &

