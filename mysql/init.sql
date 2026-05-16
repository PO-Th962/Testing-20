-- เปลี่ยนจาก clinic_system เป็น app_db ให้ตรงกับ docker-compose และ PHP
CREATE DATABASE IF NOT EXISTS app_db;
USE app_db;

-- สร้างตาราง users เหมือนเดิม
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);