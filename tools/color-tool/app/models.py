from django.db import models

# Create your models here.


class ImageModel(models.Model):
    STATUS_PROCESSING = 'processing'
    STATUS_DONE = 'done'
    STATUS_ERROR = 'error'

    id = models.AutoField(primary_key=True)
    name = models.TextField(max_length=100)
    image = models.FileField()
    user = models.TextField(max_length=100)
    name_output = models.TextField(max_length=100, blank=True, default='')
    image_output = models.FileField(blank=True, default='')
    colors = models.JSONField(max_length=250, default=list, blank=True)
    # Trạng thái xử lý: processing / done / error (phục vụ báo lỗi & tiến trình)
    status = models.CharField(max_length=20, default=STATUS_DONE)
    error_message = models.TextField(blank=True, default='')
    created_time = models.DateTimeField(auto_now_add=True)


    @classmethod
    def create(self, name, image, user, name_output, image_output, colors):
        return ImageModel.objects.create(name=name,
                                         image=image,
                                         user=user,
                                         name_output=name_output,
                                         image_output=image_output,
                                         colors=colors)

    def __str__(self):
        return f'{self.id}-{self.name}'
