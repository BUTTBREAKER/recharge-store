@echo off
echo Creando estructura de directorios para imagenes...
echo.

cd public

REM Crear directorio principal de assets
if not exist "assets" mkdir assets
cd assets

REM Crear directorios de imagenes
if not exist "images" mkdir images
cd images

REM Juegos
if not exist "games" mkdir games
cd games

REM Mobile Legends
if not exist "mobile-legends" mkdir mobile-legends
cd mobile-legends
if not exist "products" mkdir products
cd ..

REM Free Fire (futuro)
if not exist "free-fire" mkdir free-fire

REM PUBG (futuro)
if not exist "pubg" mkdir pubg

cd ..

REM Payment Methods
if not exist "payment-methods" mkdir payment-methods
cd payment-methods
if not exist "crypto-icons" mkdir crypto-icons
cd ..

REM UI Elements
if not exist "ui" mkdir ui

REM Badges
if not exist "badges" mkdir badges

cd ..
cd ..

REM Iconos SVG
cd assets
if not exist "icons" mkdir icons

echo.
echo ===================================
echo Estructura de directorios creada!
echo ===================================
echo.
echo Directorios creados en: public\assets\
echo.
echo Proximos pasos:
echo 1. Colocar las imagenes en sus respectivas carpetas
echo 2. Ejecutar: php validate-images.php
echo.
pause
