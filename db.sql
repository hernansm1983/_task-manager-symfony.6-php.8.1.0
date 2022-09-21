CREATE DATABASE IF NOT EXISTS `task-manager`;

USE `task-manager`;

CREATE TABLE IF NOT EXISTS users(
id  int(255) auto_increment not null,
role    varchar(50),
name    varchar(100),
surname varchar(200),
email   varchar(255),
password    varchar(255),
created_at  datetime,
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDB;

INSERT INTO `users` VALUES(NULL, 'ROLE_USER', 'Victor', 'Robles', 'victor@victor.com', '123456', CURRENT_TIME);
INSERT INTO `users` VALUES(NULL, 'ROLE_USER', 'Pepe', 'Parada', 'pepe@pepe.com', '123456', CURRENT_TIME);
INSERT INTO `users` VALUES(NULL, 'ROLE_USER', 'Ramon', 'Ra', 'ramon@ramon.com', '123456', CURRENT_TIME);
INSERT INTO `users` VALUES(NULL, 'ROLE_USER', 'Charly', 'Rayband', 'charly@charly.com', '123456', CURRENT_TIME);

CREATE TABLE IF NOT EXISTS tasks(
id  int(255) auto_increment not null,
user_id int(255) not null,
title varchar(255),
content text,
priority    varchar(20),
hours int(100),
created_at datetime,
CONSTRAINT pk_tasks PRIMARY KEY(id),
CONSTRAINT fk_task_user FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDb;

INSERT INTO `tasks` VALUES(NULL, 1, 'Tarea 1', 'Contenido de Prueba 1 ...', 'high', 40, CURRENT_TIME);
INSERT INTO `tasks` VALUES(NULL, 1, 'Tarea 2', 'Contenido de Prueba 2 ...', 'low', 20, CURRENT_TIME);
INSERT INTO `tasks` VALUES(NULL, 2, 'Tarea 3', 'Contenido de Prueba 3 ...', 'medium', 10, CURRENT_TIME);
INSERT INTO `tasks` VALUES(NULL, 3, 'Tarea 4', 'Contenido de Prueba 4 ...', 'high', 50, CURRENT_TIME);