import pafy
import sys
import json

arg = str(sys.argv[1])
if arg:
	video = pafy.new(arg)
	audiostreams = video.audiostreams
	for a in audiostreams:
		if a.extension == 'm4a':
                    print(json.dumps({'url':a.url, 'title':video.title, 'author':video.author, 'date':video.published}))
