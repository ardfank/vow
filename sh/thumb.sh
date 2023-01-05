#! /bin/bash
d=$(/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=duration -of csv=s=x:p=0 "$1")
dh=${d%%.*}
dd=$(( ${dh#*x} / 2 - 2 ))

if [ $dd -lt 0 ]
then dd=0
fi

if [ $(($dh - $dd)) -lt 5 ]
then dh=$(($dh - $dd))
else dh=5
fi

echo "Thumb: $dh secs from $dd"

/usr/bin/ffmpeg -y -ss "$dd" -t "$dh" -i "$1" -filter_complex "[0:v] fps=9,scale=w=72:h=-2,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1" -loop 0 -f webp "$1.gif"

exit
