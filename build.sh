#!/bin/sh

echo Compiling stylesheets...
time sass assets/scss:.
echo Done!

echo Compiling and packing JS...
time npm run build
echo Done!

exit 0
