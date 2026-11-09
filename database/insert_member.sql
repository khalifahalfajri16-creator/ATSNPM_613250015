-- Jalankan ini di phpMyAdmin (database: ats_npm613250015)
-- Untuk menambahkan akun login ke tabel member_015

USE `ats_npm613250015`;

INSERT INTO `member_015` (`email`, `password`, `nama`, `aktive`) VALUES
('admin@rental.com', MD5('admin123'), 'Administrator', '1'),
('alif@rental.com',  MD5('alif123'),  'Alif',          '1');
