
source ./bashrc

#
# intro()  
#
function intro()
{
	echo "#" > $1 
	echo "# Created by Opal on `date`"  >> $1	
	echo "#" >> $1 
	echo "" >> $1 
	echo "" >> $1 
}

#
# heading_box ( title, filename )  
#
function heading_box()
{
	cLine="################################################################################"

	echo $cLine >> $2 
	echo "#" >> $2 
	echo "# $1" >> $2	
	echo "#" >> $2 
	echo $cLine >> $2 
	echo "" >> $2
	echo "" >> $2
	echo "" >> $2
}

install_dir=`pwd`
ln -s $install_dir ~/opal
now=$(today unix)

if [ -f ~/.bashrc ]; then
	mv ~/.bashrc ~/.bashrc.${now} 
fi

if [ -f ~/.bash_profile ]; then
	mv ~/.bash_profile ~/.bash_profile.${now} 
fi

intro ~/.bashrc 

echo "Are you new to UNIX or Linux (y/n)?"
read is_new

if [ $is_new == "y" ] || [ $is_new == "Y" ]; then
	echo 'export OPAL_NOOB=1' >> ~/.bashrc
else
	echo 'export OPAL_NOOB=0' >> ~/.bashrc
fi

echo '' >> ~/.bashrc
echo 'source ~/opal/bashrc' >> ~/.bashrc
echo '' >> ~/.bashrc

heading_box 'VARIABLES' ~/.bashrc 
heading_box 'ALIASES'  ~/.bashrc
heading_box 'FUNCTIONS' ~/.bashrc
heading_box 'MAIN' ~/.bashrc


intro ~/.bash_profile 

echo '' >> ~/.bash_profile
echo 'source ~/opal/bash_profile' >> ~/.bash_profile
echo '' >> ~/.bash_profile

heading_box 'BASH PROFILE' ~/.bash_profile




