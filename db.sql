
CREATE DATABASE IF NOT EXISTS myShopDB;
CREATE TABLE IF NOT EXISTS products(
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(11) NOT NULL,
    description VARCHAR(50) NOT NULL,
    price DOUBLE(5, 2) NOT NULL,
    image VARCHAR(55) NOT NULL
);
CREATE TABLE IF NOT EXISTS comments(
    id_commnet INT(6) AUTO_INCREMENT PRIMARY KEY,
    id INT,
    comment VARCHAR(500),
    completed BOOLEAN,
    FOREIGN KEY (id) REFERENCES products(id) ON DELETE SET NULL
);
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 1","Blue","132.22","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 2","Red","132.12","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
INSERT INTO `products`(`title`, `description`, `price`, `image`) VALUES ("Flower 3","Purple","102.22","https://cdn.toxel.ro/img/contents/flowers_56.jpg");
      
CREATE TABLE orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    customer_address VARCHAR(100),
    customer_comment VARCHAR(200),
    creation_date varchar(100)
);
CREATE TABLE order_product(
    order_id INT,
    product_id INT,
    price DOUBLE(5, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

SELECT order_product.order_id,orders.creation_date, orders.customer_name, orders.customer_address, orders.customer_comment ,SUM(order_product.price) as sum_price
FROM (orders
INNER JOIN order_product ON order_product.order_id = orders.id) 
GROUP BY order_product.order_id;

SELECT orderItem.order_id,orders.creation_date, orders.customer_name, orders.adress, orders.comment, products.price, products.title, products.description, products.image
FROM ((orderItem
INNER JOIN orders ON orderItem.order_id = orders.order_id)
INNER JOIN products ON orderItem.id = products.id) WHERE orderItem.order_id = 4;
