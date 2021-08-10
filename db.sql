
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
      
CREATE TABLE IF NOT EXISTS orders(
    creation_date varchar(100),
    customer_details varchar(100),
    purchased_products int(100) PRIMARY KEY,
    FOREIGN KEY (purchased_products)
      REFERENCES products (id)
      ON DELETE CASCADE
);
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    adress VARCHAR(100),
    comment VARCHAR(200)
);
CREATE TABLE orders(
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    creation_date varchar(100),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);
CREATE TABLE orderItem(
    order_id INT,
    id INT,
    FOREIGN KEY (id) REFERENCES products(id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);