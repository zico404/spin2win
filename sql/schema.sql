CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prizes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prize_name VARCHAR(150) NOT NULL,
  color VARCHAR(20) NOT NULL DEFAULT '#D4AF37',
  weight INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sponsors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sponsor_name VARCHAR(150) NOT NULL,
  logo_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(180) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  prize_id INT NOT NULL,
  prize_name VARCHAR(150) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(prize_id)
);

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(120) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash)
VALUES ('admin', '$2y$10$WqIHKkC7i2v2r71cIw88UelMGM4J6t4rCr4tMUIBo5TI6waBwy5rq');
-- default password: admin123 (change immediately)

INSERT INTO prizes (prize_name, color, weight) VALUES
('10% Discount', '#D4AF37', 5),
('Free Shipping', '#FFD700', 3),
('VIP Gift Box', '#C9A227', 1),
('Try Again', '#8A6D1A', 8);

INSERT INTO settings (setting_key, setting_value) VALUES
('site_title', 'Spin to Win'),
('about_content', 'Welcome to our exclusive Spin to Win experience.'),
('admin_email', 'admin@example.com'),
('smtp_host', 'smtp.example.com'),
('smtp_port', '587'),
('smtp_username', ''),
('smtp_password', ''),
('smtp_encryption', 'tls'),
('smtp_from_email', 'no-reply@example.com');
