#!/usr/bin/env bash

rm -rf ./svn

mkdir -p ./svn

svn checkout $SVN_REPO svn --username=$SVN_USERNAME --password=$SVN_PASSWORD

cd ./svn

svn upgrade

version=$(cat ../VERSION.md)

echo "Starting commit for version ${version}"

cd ../

# This is also done in the build plan, but is done in case this scripts gets run locally
find . -type d | grep .git | xargs rm -rf

rsync -RrPz dist/* src/* vendor/* moovly.php README.md readme.txt package.json package-lock.json ./svn/trunk

cd ./svn

svn status

svn commit -m "Adding working dir of version ${version}"