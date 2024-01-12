DROP DATABASE IF EXISTS task_app;

CREATE DATABASE task_app;

USE task_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userNAme VARCHAR(255),
    userEmail VARCHAR(255),
    userPassword VARCHAR(255)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tasName VARCHAR(255),
    tasDate DATE,
    tasPriority CHARACTER(6),
    tasDescription VARCHAR(255),
    tasState CHARACTER(15),
    Foreign Key (user_id) REFERENCES users(id)
);
