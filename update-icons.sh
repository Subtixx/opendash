#!/bin/bash

if [ ! -d "resources/css/icons" ]; then
    git clone https://github.com/walkxcode/dashboard-icons.git resources/css/icons
fi

cd resources/css/icons || exit
git pull
