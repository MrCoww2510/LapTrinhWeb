-- =====================đã sửa=======================
-- =======================
-- TẠO DATABASE
-- =======================
CREATE DATABASE IF NOT EXISTS banhang;
USE banhang;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- =======================
-- TABLE: nhomsp
-- =======================
CREATE TABLE nhomsp (
  idnhom int(11) NOT NULL AUTO_INCREMENT,
  ten varchar(100) NOT NULL,
  thutu smallint(6) NOT NULL,
  noibat tinyint(4) NOT NULL,
  PRIMARY KEY (idnhom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO nhomsp (idnhom, ten, thutu, noibat) VALUES
(1, 'Điện thoại', 1, 1),
(2, 'Laptop', 2, 1),
(3, 'Phụ kiện', 3, 1),
(4, 'Máy in', 4, 1);

-- =======================
-- TABLE: sanpham
-- =======================
CREATE TABLE sanpham (
  idsp int(11) NOT NULL AUTO_INCREMENT,
  ten varchar(1000) NOT NULL,
  gia int(11) NOT NULL,
  hinhanh varchar(500) NOT NULL,
  thongso varchar(10000) NOT NULL,
  idnhom int(11) NOT NULL,
  PRIMARY KEY (idsp),
  KEY idnhom (idnhom),
  CONSTRAINT fk_sanpham_nhom FOREIGN KEY (idnhom) REFERENCES nhomsp(idnhom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sanpham (idsp, ten, gia, hinhanh, thongso, idnhom) VALUES
(1, 'Điện thoại samsung', 2999, 'iphone-13-pro-xanh-xa-1.jpg', 'Điện thoại samsung...', 1),
(2, 'Vivo', 34345, 'vivo-y15s-2021-tx-1.jpg', 'Vivo...', 1);

-- =======================
-- TABLE: tintuc
-- =======================
CREATE TABLE tintuc (
  id int(11) NOT NULL AUTO_INCREMENT,
  tieude varchar(1000) NOT NULL,
  noidung varchar(10000) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO tintuc (id, tieude, noidung) VALUES
(1, 'tin 1', 'tin 1...'),
(2, 'tin 2', 'tin 2...');

COMMIT;