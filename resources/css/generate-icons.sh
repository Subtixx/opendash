#!/bin/bash

rm icons.scss

for file in icons/png/*.png
do
    # Get the filename without the extension
    filename=$(basename "$file" .png)
    echo ".hl-$filename { background-image: url(icons/png/$filename.png); }" >> icons.scss
done

for file in icons/svg/*.svg
do
    # Get the filename without the extension
    filename=$(basename "$file" .svg)
    echo ".hl-$filename { background-image: url(icons/svg/$filename.svg); }" >> icons.scss
done
