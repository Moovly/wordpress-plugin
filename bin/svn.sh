#!/usr/bin/env bash

sudo yum -y update
sudo yum install -y rsync svn

rm -rf ./svn

mkdir -p ./svn

export SVN_REPO=https://plugins.svn.wordpress.org/moovly

echo $SVN_USERNAME

svn --no-auth-cache checkout $SVN_REPO svn --username $SVN_USERNAME --password $SVN_PASSWORD

cd ./svn

svn --no-auth-cache upgrade

echo "Starting commit for version ${VERSION}"

cd ../

# This is also done in the build plan, but is done in case this scripts gets run locally
find ./vendor -type d | grep .git | xargs rm -rf

rsync -RrPz dist/* src/* vendor/* moovly.php README.md readme.txt package.json package-lock.json ./svn/trunk

cd ./svn

svn --no-auth-cache status

svn --no-auth-cache commit -m "Adding working dir of version ${VERSION}"  --username $SVN_USERNAME --password $SVN_PASSWORD
