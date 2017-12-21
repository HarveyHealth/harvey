#!/usr/bin/env bash

# This file is called automatically after the provisioning of the vagrant box.
# It is ONLY called after the box is created, not when it is shut down or booted up.
sudo rm /etc/nginx/ssl/harvey.app.*
sudo cp /vagrant/vagrant_files/harvey.app.* /etc/nginx/ssl/
sudo cp /vagrant/vagrant_files/ssl.conf /etc/nginx/ssl/
sudo service nginx restart
