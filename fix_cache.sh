#!/bin/sh
# Simon-Pierre Alepin v1

# Text color variables
txtund=$(tput sgr 0 1)          # Underline
txtbld=$(tput bold)             # Bold
red=${txtbld}$(tput setaf 1) #  red
yellow=${txtbld}$(tput setaf 3) #  blue
blue=${txtbld}$(tput setaf 4) #  blue
white=${txtbld}$(tput setaf 7) #  white
green=${txtbld}$(tput setaf 2)
txtrst=$(tput sgr0)             # Reset

# info=${bldwht}*${txtrst}        # Feedback
# pass=${bldblu}*${txtrst}
# warn=${bldred}*${txtrst}
# ques=${bldblu}?${txtrst}

toLower() 
{
  echo $1 | tr "[:upper:]" "[:lower:]" 
} 
toUpper() 
{
  echo $1 | tr "[:lower:]" "[:upper:]" 
}

function setColor
{
    echo "$1$2"${txtrst}
}

setVar() 
{
    eval $1="'$2'"
}

function fiveWelcome
{
    echo " "
    setColor ${green} "*******************************************"
    setColor ${green} "    FIVE - $1"
    setColor ${green} "*******************************************"
    echo " "
}

function setPermissions
{
    askExecute "Do you want to fix cache & logs permissions? (Y/n)[$(tput setaf 2)Y$(tput sgr0)]:" "Y"
    return_perm=$?
    if [ "$return_perm" = 1 ]; then
        askApacheGroup "Specify your Apache group [$(tput setaf 2)_www$(tput sgr0)]:" _www

        sudo rm -rf app/cache/* app/logs/*
        sudo chmod +a "$GROUP allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
        sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
    fi
}

function askExecute
{        
    read -p "$1" ANSWER
    ANSWER=`toUpper $ANSWER`    
    
    if [ "$ANSWER" = '' ]; then
        #Set default
        ANSWER="$2"
    fi

    if [ "$ANSWER" = "Y" ]; then
        return 1;
    else
        return 0;        
    fi
}

function askApacheGroup
{
    while true; do
        
        if [ "${2:-}" = "_www" ]; then            
            default=_www
        else
            default=
        fi
 
        # Ask the question
        read -p "$1 ($prompt) [$default]" REPLY
 
        # Default?
        if [ -z "$REPLY" ]; then
            REPLY=$default
        fi
        
        # Check if the reply is valid
        if [ "$REPLY" != "" ]; then
            GROUP=$REPLY
            return 1
        fi

    done
}

function runMacOnly
{
    OS=$(uname)
    OS=`toLower $OS`

    if [ "$OS" != "darwin" ]; then
        setColor ${red} "*ERROR: This script runs only on MacOSX  $OS"
        exit 0
    fi
}

GROUP=_www
REPO_PATH=
PROJECT_REPO=
PROJECT_BRANCH=master


runMacOnly

fiveWelcome "Symfony Cache/Logs Permisison Fix Tool"

setPermissions