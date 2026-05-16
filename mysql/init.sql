-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS clinic_system;
USE clinic_system;

-- สร้างตาราง users (ตรวจสอบชื่อคอลัมน์ให้ตรงกับโค้ด PHP)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);