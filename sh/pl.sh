#! /bin/sh
killall mplayer
rx=$(shuf -i 0-15 -n1)
rxx=$(shuf -i 80-100 -n1)
ry=$(shuf -i 49-51 -n1)
find /media/d/vow/video/ -type f -not -path "*vid/*" -iname "*.mp4" > ~/list.m3u
#find /media/d/vow/video/ -type f -iname "*.mp4" > ~/list.m3u
#mplayer -nosound -vo null -loop 0 -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=760 -geometry $rx%:$ry% > /dev/null 2>&1 &
#mplayer -cache 384 -cache-min 80 -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=560 -geometry $rxx%:100% > /dev/null 2>&1 &
#mplayer -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=620 -geometry 48%:99% > /dev/null 2>&1 &
#mplayer -nosound -vo null -loop 0 -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=760 -geometry 0%:99% > /dev/null 2>&1 &
mplayer -cache 500 -nosound -vo null -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=480 -geometry 50%:99% > /dev/null 2>&1 &

