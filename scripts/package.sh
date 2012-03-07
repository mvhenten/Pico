#!/bin/sh
PACKAGE_DIRS="Nano Pico $1"
PACKAGE_TMP_DIR=tmp-`date +%s`;
PACKAGE_TARGET_DIR="www-resource"
PACKAGE_FILENAME=${PACKAGE_TARGET_DIR}-`date +%s`.tar.gz

for i in $PACKAGE_DIRS
do
    echo "Packing $i"
    CUR_PACKAGE_DIR=${PACKAGE_TMP_DIR}/${i}
    mkdir -p ${PACKAGE_TMP_DIR}/${i}
    cd ${i}
    git archive -0 --output ../${i}-export.zip master
    cd ..
    cd ${PACKAGE_TMP_DIR}/${i}/
    unzip -qq ../../${i}-export.zip
    rm ../../${i}-export.zip
    rm wtk* -rf
    cd ../..
done
echo "Creating archive"
rm -rf ${PACKAGE_TARGET_DIR}
mv ${PACKAGE_TMP_DIR} ${PACKAGE_TARGET_DIR}
echo "Creating ${PACKAGE_FILENAME}"
tar -czf ${PACKAGE_FILENAME} ${PACKAGE_TARGET_DIR}
rm -rf ${PACKAGE_TARGET_DIR}
