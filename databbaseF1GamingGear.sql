CREATE DATABASE GearShop;
USE GearShop;
CREATE TABLE brands(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100)
);

INSERT INTO brands(name) VALUES
('Logitech'),
('Razer'),
('Asus'),
('Corsair'),
('SteelSeries'),
('HyperX'),
('MSI'),
('DareU'),
('Akko'),
('Fuhlen');

CREATE TABLE categories(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100)
);

INSERT INTO categories(name) VALUES
('Chuột Gaming'),
('Bàn phím Gaming'),
('Tai nghe Gaming'),
('Laptop Gaming'),
('Ghế Gaming'),
('Lót chuột');
CREATE TABLE users(
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50),
	password VARCHAR(255),
	fullname VARCHAR(100),
	email VARCHAR(100),
	phone VARCHAR(20),
    gioi_tinh VARCHAR(10),
    ngay_sinh DATE,
	role VARCHAR(20)
);

INSERT INTO users(username,password,fullname,email,role) VALUES
('admin','123456','Admin','admin@gmail.com','admin'),
('user01','123456','Nguyen Van A','a@gmail.com','customer'),
('user02','123456','Tran Van B','b@gmail.com','customer'),
('user03','123456','Le Van C','c@gmail.com','customer'),
('user04','123456','Pham Van D','d@gmail.com','customer');

CREATE TABLE products(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(200),
	price DECIMAL(12,2),
	stock INT,
	brand_id INT,
	category_id INT,
	image VARCHAR(255),

	FOREIGN KEY (brand_id) REFERENCES brands(id),
	FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO products(name,price,stock,brand_id,category_id,image) VALUES
('Logitech G102 Lightsync',450000,50,1,1,'g102.jpg'),
('Logitech G Pro X Superlight',3490000,20,1,1,'gprox.jpg'),
('Razer DeathAdder Essential',790000,35,2,1,'deathadder.jpg'),
('Razer Viper Ultimate',2990000,15,2,1,'viper.jpg'),
('SteelSeries Rival 3',850000,25,5,1,'rival3.jpg'),

('Corsair K70 RGB Pro',4290000,10,4,2,'k70.jpg'),
('Razer BlackWidow V4',4590000,12,2,2,'blackwidow.jpg'),
('Akko 3087 Mechanical',1890000,18,9,2,'akko3087.jpg'),
('DareU EK87',690000,30,8,2,'ek87.jpg'),
('HyperX Alloy Origins',2890000,14,6,2,'alloy.jpg'),

('HyperX Cloud II',1990000,22,6,3,'cloud2.jpg'),
('Razer Kraken X',1290000,28,2,3,'krakenx.jpg'),
('SteelSeries Arctis 5',2990000,16,5,3,'arctis5.jpg'),
('Logitech G733 Lightspeed',3490000,11,1,3,'g733.jpg'),
('Corsair HS80 RGB',3290000,9,4,3,'hs80.jpg'),

('Asus ROG Strix G16',38990000,5,3,4,'rog16.jpg'),
('MSI Katana 15',31990000,6,7,4,'katana15.jpg'),
('Asus TUF F15',27990000,7,3,4,'tuff15.jpg'),
('MSI GF63 Thin',22990000,8,7,4,'gf63.jpg'),
('Asus ROG Zephyrus G14',45990000,4,3,4,'g14.jpg'),

('DXRacer Gaming Chair',7990000,5,3,5,'dxracer.jpg'),
('Corsair T3 Rush',8990000,4,4,5,'t3rush.jpg'),
('AKRacing Core Series',7590000,6,3,5,'akracing.jpg'),

('SteelSeries QCK',390000,40,5,6,'qck.jpg'),
('Razer Gigantus V2',450000,35,2,6,'gigantus.jpg'),
('Corsair MM300',490000,30,4,6,'mm300.jpg'),
('Logitech G640',790000,20,1,6,'g640.jpg'),
('HyperX Fury S',650000,22,6,6,'furys.jpg'),
('DareU ESP109',190000,50,8,6,'esp109.jpg'),
('Akko Mousepad XL',350000,27,9,6,'akkopad.jpg');

CREATE TABLE orders(
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT,
	total_price DECIMAL(12,2),
	status VARCHAR(50),

	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_details(
	id INT AUTO_INCREMENT PRIMARY KEY,
	order_id INT,
	product_id INT,
	quantity INT,
	price DECIMAL(12,2),

	FOREIGN KEY (order_id) REFERENCES orders(id),
	FOREIGN KEY (product_id) REFERENCES products(id)
);