-- dream_users table
CREATE TABLE dream_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(60),
    email VARCHAR(100),
    username VARCHAR(60),
    age INT,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- dreams table
CREATE TABLE dreams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES dream_users(id)
);
