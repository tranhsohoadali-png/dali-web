from django import forms
from .models import *


class LoginForm(forms.Form):
    username = forms.CharField(label='username', max_length=100)
    password = forms.CharField(label='password', max_length=100)


class ImageForm(forms.ModelForm):
    class Meta:
        model = ImageModel
        fields = "__all__"


