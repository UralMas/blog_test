CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(500) DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content LONGTEXT,
    views INT UNSIGNED DEFAULT 0,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_published (published_at)
) ENGINE=InnoDB;

CREATE TABLE post_category (
    category_id INT UNSIGNED NOT NULL,
    post_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (category_id, post_id),
    INDEX idx_category_id (category_id),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;