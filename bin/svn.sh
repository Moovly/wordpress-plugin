#!/usr/bin/env bash

VERSION=$1
sudo yum -y update
sudo yum install -y rsync svn

# This is also done in the build plan, but is done in case this scripts gets run locally
find ./vendor -type d | grep .git | xargs rm -rf

rm -rf ./svn

mkdir -p ./svn

export SVN_REPO=https://plugins.svn.wordpress.org/moovly

echo $SVN_USERNAME

svn --no-auth-cache checkout $SVN_REPO svn --username $SVN_USERNAME --password $SVN_PASSWORD

cd ./svn

svn --no-auth-cache upgrade

svn --no-auth-cache update

rm -Rf trunk
mkdir trunk

cp -R ../dist trunk
cp -R ../src trunk
cp -R ../vendor trunk
cp ../moovly.php trunk
cp ../README.md trunk
cp ../readme.txt trunk
cp ../package.json trunk
cp ../package-lock.json trunk

#polyfill php70 conflicts on svn commit
rm -rf trunk/vendor/symfony/polyfill-php70/Resources/

# DO THE ADD ALL NOT KNOWN FILES UNIX COMMAND
svn --no-auth-cache add --force * --auto-props --parents --depth infinity -q

# DO THE REMOVE ALL DELETED FILES UNIX COMMAND
MISSING_PATHS=$( svn --no-auth-cache status | sed -e '/^!/!d' -e 's/^!//' )

# iterate over filepaths
for MISSING_PATH in $MISSING_PATHS; do
    svn --no-auth-cache rm --force "$MISSING_PATH"
done

# COPY TRUNK TO TAGS/$VERSION
echo "Copying trunk to new tag"
svn copy trunk tags/${VERSION}

svn --no-auth-cache status

echo "Starting commit for version $VERSION"
svn --no-auth-cache commit -m "Adding working dir of version $VERSION"  --username $SVN_USERNAME --password $SVN_PASSWORD
