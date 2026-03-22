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
	travelid VARCHAR(10),
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

CREATE TABLE thongso(
	id INT AUTO_INCREMENT PRIMARY KEY,
	product_id INT,

	cpu VARCHAR(100),
	ram VARCHAR(50),
	vga VARCHAR(100),
	storage VARCHAR(100),
	screen VARCHAR(100),
	battery VARCHAR(100),
	weight VARCHAR(50),

	FOREIGN KEY (product_id) REFERENCES products(id)
);
INSERT INTO thongso(product_id,cpu,ram,vga,storage,screen,battery,weight) VALUES

-- ===== CHUỘT =====
(1,NULL,NULL,NULL,NULL,NULL,'AA','85g'),
(2,NULL,NULL,NULL,NULL,NULL,'Rechargeable','63g'),
(3,NULL,NULL,NULL,NULL,NULL,NULL,'96g'),
(4,NULL,NULL,NULL,NULL,NULL,'Dock Charging','74g'),
(5,NULL,NULL,NULL,NULL,NULL,NULL,'77g'),

-- ===== BÀN PHÍM =====
(6,NULL,NULL,NULL,NULL,NULL,NULL,'1.2kg'),
(7,NULL,NULL,NULL,NULL,NULL,NULL,'1.1kg'),
(8,NULL,NULL,NULL,NULL,NULL,NULL,'0.9kg'),
(9,NULL,NULL,NULL,NULL,NULL,NULL,'0.8kg'),
(10,NULL,NULL,NULL,NULL,NULL,NULL,'1.0kg'),

-- ===== TAI NGHE =====
(11,NULL,NULL,NULL,NULL,NULL,'30h','320g'),
(12,NULL,NULL,NULL,NULL,NULL,NULL,'250g'),
(13,NULL,NULL,NULL,NULL,NULL,NULL,'280g'),
(14,NULL,NULL,NULL,NULL,NULL,'Wireless','278g'),
(15,NULL,NULL,NULL,NULL,NULL,NULL,'330g'),

-- ===== LAPTOP =====
(16,'Intel Core i7-13650HX','16GB','RTX 4060','512GB SSD','16" FHD 165Hz','90Wh','2.5kg'),
(17,'Intel Core i7-12650H','16GB','RTX 4050','512GB SSD','15.6" FHD 144Hz','53Wh','2.25kg'),
(18,'Intel Core i5-12500H','8GB','RTX 3050','512GB SSD','15.6" FHD 144Hz','48Wh','2.2kg'),
(19,'Intel Core i5-11400H','8GB','GTX 1650','512GB SSD','15.6" FHD','51Wh','1.86kg'),
(20,'Ryzen 9 7940HS','16GB','RTX 4060','1TB SSD','14" QHD 165Hz','76Wh','1.65kg'),

-- ===== GHẾ =====
(21,NULL,NULL,NULL,NULL,NULL,NULL,'25kg'),
(22,NULL,NULL,NULL,NULL,NULL,NULL,'24kg'),
(23,NULL,NULL,NULL,NULL,NULL,NULL,'23kg'),

-- ===== LÓT CHUỘT =====
(24,NULL,NULL,NULL,NULL,NULL,NULL,'200g'),
(25,NULL,NULL,NULL,NULL,NULL,NULL,'250g'),
(26,NULL,NULL,NULL,NULL,NULL,NULL,'260g'),
(27,NULL,NULL,NULL,NULL,NULL,NULL,'240g'),
(28,NULL,NULL,NULL,NULL,NULL,NULL,'230g'),
(29,NULL,NULL,NULL,NULL,NULL,NULL,'180g'),
(30,NULL,NULL,NULL,NULL,NULL,NULL,'210g');