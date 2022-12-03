#! /bin/bash

/usr/bin/ffmpeg -ss 3 -t 6 -i "$1" -filter_complex "[0:v] fps=9,scale='if(gt(iw,ih),-1,256):if(gt(iw,ih),256,-1)',crop=256:256:exact=1,split [a][b];[a] palettegen=stats_mode=single [p];[b][p] paletteuse=new=1" -loop 3 '/media/d/test.webp' -y

exit 0
