@echo off
set "PATH=C:\Users\NFDorottya\.config\herd\bin\php84;%PATH%"
cd /d "E:\FogaszatiRendelo\fogaszat-web"
"C:\Users\NFDorottya\.config\herd\bin\php84\php.exe" artisan serve --port=8001
