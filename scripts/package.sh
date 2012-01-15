#!/bin/sh
PACKAGE_DIRS="Nano Pico $1"
PACKAGE_TMP_DIR=tmp-`date +%s`;
PACKAGE_TARGET_DIR="www-resource"
for i in $PACKAGE_DIRS
do
    echo "Packing $i"
    mkdir -p ${PACKAGE_TMP_DIR}/${i}
    cd ${i}
    git archive -0 --output ../${i}-export.zip master
    cd ..
    cd ${PACKAGE_TMP_DIR}/${i}/
    unzip -qq ../../${i}-export.zip
    rm ../../${i}-export.zip
    cd ../..
done
echo "Creating archive"
rm -rf ${PACKAGE_TARGET_DIR}
mv ${PACKAGE_TMP_DIR} ${PACKAGE_TARGET_DIR}
tar -czf ${PACKAGE_TARGET_DIR}-`date +%s`.tar.gz ${PACKAGE_TARGET_DIR}
rm -rf ${PACKAGE_TARGET_DIR}
