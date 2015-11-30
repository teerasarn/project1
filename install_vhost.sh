#!/bin/sh

# Text color variables
txtund=$(tput sgr 0 1)          # Underline
txtbld=$(tput bold)             # Bold
bldred=${txtbld}$(tput setaf 1) #  red
red=${txtbld}$(tput setaf 1) #  red
bldblu=${txtbld}$(tput setaf 4) #  blue
blue=${txtbld}$(tput setaf 4) #  blue
bldwht=${txtbld}$(tput setaf 7) #  white
txtrst=$(tput sgr0)             # Reset
green=${txtbld}$(tput setaf 2)
info=${bldwht}*${txtrst}        # Feedback
pass=${bldblu}*${txtrst}
warn=${bldred}*${txtrst}
ques=${bldblu}?${txtrst}


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

function ask_with_default
{
    while true; do
        read -p "$1" ANSWER
        # VARNAME="${2}"
        # echo "Varname: \$${VARNAME}"
        # echo "Answer: $ANSWER"
        
        if [ "$ANSWER" = '' ]; then
            #ANSWER= $2
            #ANSWER="'$2'"

            # this is the equivalent of e.g. ANSWER=$WEBROOT, need to add eval + \$
            eval ANSWER=\$$2
            #eval echo \$$2
            #echo "Ans $ANSWER"
        fi

        if [ "$ANSWER" != '' ]; then
            #Finally found a way to set dynamic var, $2 is like WEBROOT !
            eval $2="'$ANSWER'"
            return 1;
        fi
    done
}

function removeDirectoryOrLink
{
    if [ -d "$1" ]; then 
        if [ -L "$1" ]; then
            # It is a symlink
            rm "$1"
        else
            # It's a directory
            rmdir "$1"
        fi
    fi    
}

function makeDir
{
    pathExists "$1"
    return_val=$?

    if [ "$return_val" = 0 ]; then
        mkdir -p "$1"

        pathExists "$1"
        return_val=$?
        return $return_val
    else
        return 0
    fi
}

function pathExists
{
    if [ -d "$1" ]; then 
        if [ -L "$1" ]; then
            # It is a symlink
            return 1
        else
            # It's a directory
            return 1
        fi
    else
        return 0
    fi     
}

function ask_execute
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

function runMacOnly
{
    OS=$(uname)
    OS=`toLower $OS`

    if [ "$OS" != "darwin" ]; then
        setColor ${red} "*** Error: This script runs only on MacOSX  $OS"
        exit 0
    fi
}

function fiveWelcome
{
    echo " "
    setColor ${green} "*******************************************"
    setColor ${green} "    FIVE - VHOST SETUP TOOL"
    setColor ${green} "*******************************************"
    echo " "
}

runMacOnly


DOCROOT=/Library/WebServer/Documents/
SITESROOT=/Users/${USER}/Sites/
#PROJECTS=/Users/simon/Projects/
CURRENT_FOLDER=$(cd "$1"; pwd)
LCURRENT_FOLDER=`toLower ${PWD##*/}`
WEBROOT=$CURRENT_FOLDER/web
SYMLINKNAME=$DOCROOT${PWD##*/}
SERVERNAME=
HOST="${LCURRENT_FOLDER}.localhost"


#Determine Apache sites root
pathExists "$DOCROOT"
return_docroot=$?

pathExists "$SITESROOT"
return_sitesroot=$?

if [[ "$return_docroot" = 0 && "$return_sitesroot" = 1 ]]; then
    DOCROOT="$SITESROOT"
fi

fiveWelcome
ask_with_default "Input your project WEB ROOT projectpath [$(tput setaf 2)${WEBROOT}$(tput sgr0)]: " WEBROOT
ask_with_default "Input your symlink path to your apache root [$(tput setaf 2)${SYMLINKNAME}$(tput sgr0)]: " SYMLINKNAME
ask_with_default "Servername for your site [$(tput setaf 2)${HOST}$(tput sgr0)]: " HOST



if [ "$SYMLINKNAME" != '' ]; then
    ask_execute "Do you want to create Apache symlink [$(tput setaf 2)${SYMLINKNAME}$(tput sgr0)] to webroot [$(tput setaf 2)${WEBROOT}$(tput sgr0)]? (Y/n)[$(tput setaf 2)Y$(tput sgr0)]:" "Y"
    return_val=$?
    echo "${return_val}  for Apache"
    if [ "$return_val" -eq 1 ]; then
        echo
        echo "$(tput setaf 6)--> Creating Symlink$(tput sgr0)"
        echo "    Project Web root: $(tput setaf 2)${WEBROOT}$(tput sgr0)"
        echo "    Apache symlink: $(tput setaf 2)${SYMLINKNAME}$(tput sgr0)"    
        echo
        ln -s "${PROJECTS}${WEBROOT}" "${SYMLINKNAME}"

        #echo
        #echo "$(tput setaf 6)--> Set Permissions for Apache folder$(tput sgr0)"  
        #echo
        #ln -s "${PROJECTS}${WEBROOT}" "${SYMLINKNAME}"        
    fi
fi

read -d '' VHOSTCODE <<EOF

<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    ServerName ${HOST}
    DocumentRoot "${SYMLINKNAME}"
    <Directory ${SYMLINKNAME}>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
    ErrorLog /var/log/apache2/${HOST}-error.log
    LogLevel warn
    CustomLog /var/log/apache2/${HOST}-access.log combined
</VirtualHost>
EOF

if [ "$HOST" != '' ]; then
    ask_execute "Do you want to add $(tput setaf 6)${HOST}$(tput sgr0) your hosts file? (Y/n)[$(tput setaf 2)Y$(tput sgr0)]:" "Y"
    return_val=$?

    if [[ "$return_val" = 1 || "$return_val" = "Y" || "$return_val" = "y" ]]; then
        echo " "
        echo "$(tput setaf 6)--> Updating /private/etc/hosts file$(tput sgr0)"
        echo "    127.0.0.1 $(tput setaf 6)${HOST}$(tput sgr0)"
        echo " "
        #sudo echo "" >> /private/etc/hosts
        sudo echo "127.0.0.1 ${HOST}" >> /private/etc/hosts
    fi
fi


if [ "$SYMLINKNAME" != '' ]; then    
    ask_execute "Do you want to setup $(tput setaf 6)${HOST}$(tput sgr0) in your apache vhost file? (Y/n)[$(tput setaf 2)Y$(tput sgr0)]:" "Y"
    return_val=$?

    if [[ "$return_val" = 1 || "$return_val" = "Y" || "$return_val" = "y" ]]; then
        echo " "
        echo "$(tput setaf 6)--> Adding vhost /etc/apache2/extra/httpd-vhosts.conf$(tput sgr0)"
        echo " "
        #echo -e " " >> /etc/apache2/extra/httpd-vhosts.conf
        sudo echo " " >> /etc/apache2/extra/httpd-vhosts.conf
        sudo echo "${VHOSTCODE}" >> /etc/apache2/extra/httpd-vhosts.conf
#         sudo echo "<VirtualHost *:80>
#     ServerAdmin webmaster@localhost
#     ServerName ${HOST}
#     DocumentRoot \"${SYMLINKNAME}\"
#     <Directory ${SYMLINKNAME}>
#         Options -Indexes +FollowSymLinks
#         AllowOverride All
#         Order allow,deny
#         allow from all
#     </Directory>
#     ErrorLog /var/log/apache2/${HOST}-error.log
#     LogLevel warn
#     CustomLog /var/log/apache2/${HOST}-access.log combined
# </VirtualHost>" >> /etc/apache2/extra/httpd-vhosts.conf
    fi
fi

if [ "$SYMLINKNAME" != '' ]; then
    ask_execute "Do you want to restart Apache? (Y/n)[$(tput setaf 2)Y$(tput sgr0)]:" "Y"
    return_val=$?

    if [[ "$return_val" = 1 || "$return_val" = "Y" || "$return_val" = 'y' ]]; then    
        echo
        echo "$(tput setaf 6)--> Restarting Apache$(tput sgr0)"
        echo
        sudo apachectl restart
    fi
fi

echo
echo "Done! Test by going at $(tput setaf 2)http://${HOST}$(tput sgr0)"
echo