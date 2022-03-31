# QuicktDBTeigi

QuicktDBTeigi is software that allows you to output MySQL table definitions using only PHP, without relying on any other libraries.  
With a simple setup, table definitions are output as HTML files.

Author: hoku ( http://hoku.in/ )


# Usage

```Shell
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


# Demo

### Demo DDL

```SQL
CREATE DATABASE sample_bbs DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_as_ci;

CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    is_deleted TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,

    INDEX(email),
    INDEX(is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_as_ci;

CREATE TABLE IF NOT EXISTS boards (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_deleted  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL,
    updated_at  DATETIME,

    INDEX(is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_as_ci;

CREATE TABLE IF NOT EXISTS threads (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    board_id   INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL COMMENT 'poster',
    title      VARCHAR(255) NOT NULL,
    content    TEXT NOT NULL,
    is_deleted TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    
    INDEX(board_id),
    INDEX(user_id),
    INDEX(is_deleted),

    FOREIGN KEY fk_board_id(board_id) REFERENCES boards(id),
    FOREIGN KEY fk_user_id (user_id)  REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_as_ci;

CREATE TABLE IF NOT EXISTS comments (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    thread_id  INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL COMMENT 'poster',
    content    TEXT NOT NULL,
    is_deleted TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,

    INDEX(thread_id),
    INDEX(user_id),
    INDEX(is_deleted),
    
    FOREIGN KEY fk_thread_id(thread_id) REFERENCES threads(id),
    FOREIGN KEY fk_user_id  (user_id)   REFERENCES users  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_as_ci;
```

### Demo URL

The output of the table definitions from the database created by the DDL above will look like this HTML.

http://hoku.in/quickdbteigi/db_teigi_sample.html


# License

This software is licensed under MIT License.
