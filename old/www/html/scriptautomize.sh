echo $0
echo $1
echo $2
echo $3
echo $4

if [ $1 != "" ]; then
	sudo scp joshua@trans-dev.uofnkona.edu:/var/www/transportation/html/html/$1 $1_02.php
	sudo mv $1 $1_03.php
	sudo mv $1_02.php $1
else
	echo "You need parameter setting: the file name."
fi
