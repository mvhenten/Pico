# quick hack since sqlt won't quote tables and
# indexes must have uniqe names since they're tables too
# it converts pico db just good enough
sqlt -f MySQL -t SQLite pico-mysql40.sql \
| sed -E 's/INDEX (\w+)/INDEX \1_index/g' \
| sed -E 's/TABLE (\w+)/TABLE `\1`/g' \
| sed -E "s/([a-z_]+)\s/'\1' /g" \
> pico-sqlite.sql