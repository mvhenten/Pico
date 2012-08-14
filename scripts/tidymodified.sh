#!/bin/sh
THIS_DIRNAME=`dirname $0`;
for f in `git status -s | grep -E '^.?[MA].+\.php' | sed -e s/^..//g`;
    
    do echo "php ${THIS_DIRNAME}/phptidy.php replace $f"\
        && OK=`php ${THIS_DIRNAME}/phptidy.php replace $f`;
done;
