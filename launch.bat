@echo off
:: Chemin vers PHP 8.4 nouvellement installe
set "PHP_BIN=C:\wamp64\bin\php\php8.4.21\php.exe"
set "PROJECT_DIR=c:\wamp64\www\agence-immobiliere"
set "URL=http://127.0.0.1:8000"

echo ======================================================
echo   LANCEMENT DE L'APPLICATION IMMOPRO
echo ======================================================
echo.

cd /d "%PROJECT_DIR%"

:: Lancer le serveur Laravel dans une nouvelle fenêtre
echo [..] Demarrage du serveur ImmoPro...
start "IMMOPRO - SERVEUR" cmd /c "%PHP_BIN% artisan serve"

:: Lancer Vite pour les styles et scripts
echo [..] Demarrage de Vite (Assets)...
start "IMMOPRO - ASSETS" cmd /c "npm run dev"

echo.
echo [OK] Les serveurs demarrent.
echo [..] Ouverture du navigateur dans 5 secondes...
timeout /t 5 /nobreak > nul

start %URL%

exit
