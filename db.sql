
CREATE DATABASE IF NOT EXISTS myShopDB;
CREATE TABLE IF NOT EXISTS products(
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(11) NOT NULL,
    description VARCHAR(50) NOT NULL,
    price DOUBLE(5, 2) NOT NULL,
    image VARCHAR(55) NOT NULL
);
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 1","Blue","132.22","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 2","Red","132.12","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 3","Purple","102.22","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
      