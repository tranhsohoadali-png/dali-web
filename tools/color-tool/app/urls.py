from django.urls import path

from app import views

urlpatterns = [

    path("login", views.login_request, name="login"),
    path("logout", views.logout_request, name="logout"),
    path("homepage", views.homepage, name="homepage"),
    path("", views.homepage, name="homepage"),
    path("result", views.get_result, name="result"),
    path("export-colors", views.export_colors, name="export_colors"),
    path("export-xlsx", views.export_xlsx, name="export_xlsx"),
    path("download-legend", views.download_legend, name="download_legend"),
    path("dali-colors", views.dali_colors, name="dali_colors"),
    path("download-result", views.get_download_result, name="get_download_result"),
]
