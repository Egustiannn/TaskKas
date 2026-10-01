-- TaskKas database (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS taskkas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taskkas;

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS classes;

CREATE TABLE classes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  class_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',   -- admin = bendahara
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  subject VARCHAR(80) NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT NULL,
  deadline DATE NOT NULL,
  priority ENUM('rendah','sedang','tinggi') NOT NULL DEFAULT 'sedang',
  status ENUM('belum','selesai') NOT NULL DEFAULT 'belum',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_user_status (user_id, status),
  INDEX idx_deadline (deadline),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  class_id INT UNSIGNED NOT NULL,
  type ENUM('pemasukan','pengeluaran') NOT NULL,
  category VARCHAR(50) NOT NULL DEFAULT 'lainnya',    -- iuran, donasi, operasional, lainnya
  member_id INT UNSIGNED NULL,                         -- siswa pembayar (untuk iuran)
  amount DECIMAL(12,2) NOT NULL,
  description VARCHAR(255) NOT NULL,
  trx_date DATE NOT NULL,
  created_by INT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_class_date (class_id, trx_date),
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===== Data contoh (password semua akun: password123) =====
INSERT INTO classes (name) VALUES ('XI IPA 1');

INSERT INTO users (class_id, name, email, password, role) VALUES
(1,'Sari (Bendahara)','admin@taskkas.test','$2b$12$BdfeAqQ.vYbziVuOmK8w2OqgUV/LL.umIs0hHOPrK6Z9eu3ZbgRIa','admin'),
(1,'Budi Santoso','user@taskkas.test','$2b$12$BdfeAqQ.vYbziVuOmK8w2OqgUV/LL.umIs0hHOPrK6Z9eu3ZbgRIa','user'),
(1,'Ani Lestari','ani@taskkas.test','$2b$12$BdfeAqQ.vYbziVuOmK8w2OqgUV/LL.umIs0hHOPrK6Z9eu3ZbgRIa','user');

INSERT INTO tasks (user_id, subject, title, description, deadline, priority, status) VALUES
(2,'Matematika','Latihan Soal Trigonometri','Halaman 45 no. 1-20',DATE_ADD(CURDATE(), INTERVAL 3 DAY),'tinggi','belum'),
(2,'Bahasa Indonesia','Resensi Novel',NULL,DATE_ADD(CURDATE(), INTERVAL 7 DAY),'sedang','belum'),
(2,'Fisika','Laporan Praktikum','Praktikum gerak lurus',DATE_SUB(CURDATE(), INTERVAL 1 DAY),'sedang','selesai');

INSERT INTO transactions (class_id, type, category, member_id, amount, description, trx_date, created_by) VALUES
(1,'pemasukan','iuran',2,50000,'Iuran bulanan - Budi',DATE_SUB(CURDATE(), INTERVAL 10 DAY),1),
(1,'pemasukan','iuran',3,50000,'Iuran bulanan - Ani',DATE_SUB(CURDATE(), INTERVAL 9 DAY),1),
(1,'pemasukan','donasi',NULL,1000000,'Donasi alumni',DATE_SUB(CURDATE(), INTERVAL 8 DAY),1),
(1,'pengeluaran','operasional',NULL,150000,'Beli spidol & penghapus',CURDATE(),1);
