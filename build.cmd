@echo off

echo Compiling stylesheets...
sass assets/scss:.
echo Done!

echo Compiling and packing JS...
npm run build
echo Done!

pause