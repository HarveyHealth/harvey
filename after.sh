#!/usr/bin/env bash
sudo rm /etc/nginx/ssl/harvey.app.*
sudo cp /vagrant/vagrant_files/harvey.app.* /etc/nginx/ssl/
sudo service nginx restart