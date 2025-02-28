CREATE DATABASE jouture_beauty;
USE jouture_beauty;

CREATE TABLE `jouture_beauty`.`users` (
    `id` INT NOT NULL AUTO_INCREMENT, 
    `firstname` VARCHAR(35) NOT NULL, 
    `lastname` VARCHAR(35) NOT NULL, 
    `password` VARCHAR(255) NOT NULL, 
    `email` VARCHAR(100) NOT NULL, 
    `role` VARCHAR(60) NOT NULL, 
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)) ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jouture_beauty`.`inventory` (
    `item_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `quantity` INT NOT NULL ,
    `price` DECIMAL(10,2) NOT NULL,
    `created_by` INT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE) ENGINE = InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`firstname`, `lastname`, `password`, `email`, `role`) 
VALUES ('Janoya', 'Rowe', '$2y$10$FW/nJCZhGpMGVVw2RMKsqOpgNM1U2jooR4IbG9IXF5jhrfY/GzRQi', 'janoya.rowe@jouturebeauty.com', 'admin');

INSERT INTO `inventory`(`name`, `description`, `quantity`, `price`, `created_by`)
VALUES ('Beaded Bracelets', 'Customize your bracelet(s) with any colour in any style!', 10, 400.00, 1),
('Lipglosses', 'Keep your lips shiny and moisturized!', 20, 1000.00, 1),
('Satin Bonnets', 'Protect your hair in style!', 9, 700.00, 1),
('Reversable Bonnets', 'Why choose? Two ways to protect your hair in style!', 30, 1000.00, 1),
('Scrunchies', 'Ponytails, puffs and twists; catch them all!', 45, 100.00, 1),
('Keychain Puffs', 'Do not have boring keys!', 10, 250.00, 1);