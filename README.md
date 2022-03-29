# QuicktDBTeigi

QuicktDBTeigi is software that allows you to output MySQL table definitions using only PHP, without relying on any other libraries.  
With a simple setup, table definitions are output as HTML files.

Author: hoku ( http://hoku.in/ )


# Usage

```
# Get source.
git clone https://github.com/hoku/quickt-db-teigi.git

# Create a config.
cp quickt-db-teigi/config.json.example quickt-db-teigi/config.json

# Register DB connection information in the configuration file.
vi quickt-db-teigi/config.json

# Execute.
php quickt-db-teigi/make_db_teigi.php
```

Simply executing the above will output "db_teigi.html".

Since "db_teigi.html" contains definitions for all tables, if you want to share the definition information with others, you only need to share this HTML file to them.


# License

This software is licensed under MIT License.
