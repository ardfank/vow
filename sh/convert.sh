#! /bin/bash

for file in /media/d/vow/video/*.mp4
do
	h=$(/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=height -of csv=s=x:p=0 "$file")
	if (($h > 760)); then
		echo "$h Lebih. Memproses $file"
		/usr/bin/ffmpeg -y -i "$file" -vf "scale=-2:760" -movflags faststart -preset veryfast -crf 24 -c:v libx264 -an "$file-hs.mp4"
		/usr/bin/mv -f "$file-hs.mp4" "$file"
	else
		echo "$h, $file tidak diproses"
	fi
done
