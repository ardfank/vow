#! /bin/bash
h=$(/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=height -of csv=s=x:p=0 "$1")
# d=$(/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 | xargs printf %.0f)
#echo "Create GIF for $1"
#/usr/bin/ffmpeg -y -ss 3 -t 6 -i "$1" -filter_complex "[0:v] fps=9,scale=w=72:h=-2,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1" -loop 0 -f webp "$1.gif"
if (($h > 760)); then
	echo "Height is $h"
	echo "Scaling...."
	/usr/bin/ffmpeg -y -i "$1" -vf "scale=-2:760" -movflags faststart -preset veryfast -crf 24 -c:v libx264 -c:a copy "$1-hs.mp4" && /usr/bin/mv -f "$1-hs.mp4" "$1" && echo "Done - selesai - OK"
else
	echo "Height is $h"
	echo "No scaling - Done - selesai - OK"
fi
exit
