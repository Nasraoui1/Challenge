-- Table users
CREATE TABLE IF NOT EXISTS chall_users (
    id SERIAL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255),
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    birthdate DATE,
    address VARCHAR(255),
    phone VARCHAR(20)
);

-- Table Categories
CREATE TABLE IF NOT EXISTS chall_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

-- Table Tags
CREATE TABLE IF NOT EXISTS chall_tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table Articles
CREATE TABLE IF NOT EXISTS chall_articles (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    published_at TIMESTAMP,
    author_id INT NOT NULL,
    status VARCHAR(50) CHECK (status IN ('draft', 'published')) DEFAULT 'draft',
    FOREIGN KEY (author_id) REFERENCES chall_users(id) ON DELETE SET NULL
);

-- Table Comments
CREATE TABLE IF NOT EXISTS chall_comments (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    author_id INT NOT NULL,
    article_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES chall_users(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES chall_articles(id) ON DELETE CASCADE
);

-- Table Articles_Categories
CREATE TABLE IF NOT EXISTS chall_articles_categories (
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES chall_articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES chall_categories(id) ON DELETE CASCADE
);

-- Table Articles_Tags
CREATE TABLE IF NOT EXISTS chall_articles_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES chall_articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES chall_tags(id) ON DELETE CASCADE
);

-- Table Media
CREATE TABLE IF NOT EXISTS chall_media (
    id SERIAL PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    url VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES chall_users(id) ON DELETE SET NULL
);

-- Table Pages
CREATE TABLE IF NOT EXISTS chall_pages (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    published_at TIMESTAMP,
    author_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES chall_users(id) ON DELETE SET NULL
);

-- Table ContactMessages
CREATE TABLE IF NOT EXISTS chall_contactMessages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Settings
CREATE TABLE IF NOT EXISTS chall_settings (
    id SERIAL PRIMARY KEY,
    setting_name VARCHAR(255) NOT NULL,
    setting_value TEXT NOT NULL
);

-- Table ActivityLog
CREATE TABLE IF NOT EXISTS chall_activityLog (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES chall_users(id) ON DELETE CASCADE
);

-- Table Image
CREATE TABLE IF NOT EXISTS chall_image (
    id_image SERIAL PRIMARY KEY,
    chemin_image VARCHAR(255)
);
