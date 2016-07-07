#-*- coding: utf-8 -*-
from PIL import Image, ImageFilter

class MyGaussianBlur(ImageFilter.Filter):
    name = "GaussianBlur"
    def __init__(self, radius=2, bounds=None):
        self.radius = radius
        self.bounds = bounds
    def filter(self, image):
        if self.bounds:
            clips = image.crop(self.bounds).gaussian_blur(self.radius)
            image.paste(clips, self.bounds)
            return image
        else:
            return image.gaussian_blur(self.radius)

simg = 'demo.jpg'
dimg = 'demo_blur.jpg'
image = Image.open(simg)

# 裁剪后生成高斯图
w, h = image.size
image.thumbnail((w//5, h//5))
image.save('./thumbnail.jpg', 'jpeg')

simg = 'thumbnail.jpg';
image = Image.open(simg)
image = image.filter(MyGaussianBlur(radius=30))
image.save(dimg)
print dimg, 'success'
