from PIL import Image
from PIL.Image import Palette

img = Image.open('/home/thiennt/Pictures/1.png')
img.show('origin')
img_com = img.convert('P', palette=Palette.ADAPTIVE, colors=60)
img_com.show("test")
img_com.save('/home/thiennt/Desktop/test.PNG', format="PNG")
