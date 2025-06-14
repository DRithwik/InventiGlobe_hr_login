-- Create the database
CREATE DATABASE IF NOT EXISTS hr_management;
USE hr_management;

-- Create employees table
CREATE TABLE IF NOT EXISTS employees (
    emp_id VARCHAR(20) PRIMARY KEY,
    emp_name VARCHAR(100) NOT NULL,
    emp_phone VARCHAR(20) NOT NULL,
    emp_role VARCHAR(100) NOT NULL,
    emp_gmail VARCHAR(100) NOT NULL UNIQUE,
    emp_join_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 