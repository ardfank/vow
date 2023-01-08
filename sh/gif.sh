#! /bin/bash
inc=0
for file in /media/d/vow/video/vid/*.mp4
do
    # /usr/bin/ffmpeg -y -ss 3 -t 6 -i "$file" -filter_complex "[0:v] fps=9,scale=w=72:h=-2,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1" -loop 0 -f webp "$file.gif"
	((inc++))
	echo "[ $inc ] ${file#/media/d/vow/video/*}" | logger -t MOV
	bash ${BASH_SOURCE%/*}/thumb.sh "$file" | logger -t MOV
done
exit

#bash ${BASH_SOURCE%/*}/thumb.sh