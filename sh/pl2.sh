#! /bin/sh
killall mplayer
find /media/d/vow/video/vid -type f -iname "*.mp4" > ~/list.m3u
mplayer -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=760 -geometry 0%:100% > /dev/null 2>&1 &
mplayer -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale=w=-2:h=760 -geometry 100%:100% > /dev/null 2>&1 &
#mplayer -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale -zoom -xy 420 -geometry 30%:50% > /dev/null 2>&1 &
#mplayer -loop 0 -shuffle -playlist ~/list.m3u -vo fbdev -vf scale -zoom -xy 420 -geometry 0%:50% &

