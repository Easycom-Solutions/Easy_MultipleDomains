This folder contains everyhting to test this module or contribute easily to it.
The vagrant file will create a virtual machine with all required services and install the latest version of magento to let you test the module.

install.sh script handle magento installation with magerun and some other stuff for travis-ci

## How to use the vagrant box ?
You need : 
* Git
* VirtualBox
* Vagrant
* "hostmanager" plugin for vagrant 
* "bindfs" plugin for vagrant 

```
vagrant plugin install vagrant-hostmanager
vagrant plugin install vagrant-bindfs
```

You can customize the Vagrantfile as you want, for example to change the magento version, or php version

Then go in a terminal to {project}/develop and run 
```
vagrant up
```

## How to execute tests ?
This module is based on Functional Testing Driven Developpement (FTDD) approach; Tests are based on selenium.

I recommand to execute tests on host and not on VM, because you can see whats happen on host with firefox, that not the case on the VM.
You need on the Host :
* Firefox
* PHP (installed by default on OSX)
* Selenium Server
```
wget http://selenium-release.storage.googleapis.com/2.45/selenium-server-standalone-2.45.0.jar
java -jar /tmp/selenium-server-standalone-2.45.0.jar
```

You just need to have to run selenium server and firefox installed 