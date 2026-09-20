@echo off
title JobMatch AI - Lanceur Local
echo ========================================================
echo        Lancement de JobMatch AI (Local Dev)
echo ========================================================
echo.

:: Verification si Python tourne deja sur le port 8000
netstat -ano | findstr :8000 | findstr LISTENING >nul
if %ERRORLEVEL% EQU 0 (
    echo [OK] Microservice Python IA deja actif sur le port 8000.
) else (
    echo 1. Demarrage du microservice Python IA sur http://127.0.0.1:8000 ...
    start "JobMatch AI - Microservice IA" cmd /k "cd /d %~dp0ai-service && python main.py"
    timeout /t 3 /nobreak >nul
)

:: Verification si Laravel tourne deja sur le port 8088
netstat -ano | findstr :8088 | findstr LISTENING >nul
if %ERRORLEVEL% EQU 0 (
    echo [OK] Serveur Web Laravel deja actif sur le port 8088.
) else (
    echo 2. Demarrage du serveur Web Laravel sur http://127.0.0.1:8088 ...
    start "JobMatch AI - Web Application" cmd /k "cd /d %~dp0backend && php artisan serve --host=127.0.0.1 --port=8088"
    timeout /t 2 /nobreak >nul
)

echo 3. Ouverture de JobMatch AI dans votre navigateur...
start http://127.0.0.1:8088

echo.
echo ========================================================
echo   JobMatch AI est pret !
echo   - Interface Web : http://127.0.0.1:8088
echo   - Documentation API : http://127.0.0.1:8000/docs
echo ========================================================
