
-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    password VARCHAR(100) NOT NULL,  -- Adding password field
    role ENUM('user', 'admin') NOT NULL,  -- Using ENUM for roles
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create kosts table
CREATE TABLE kosts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_kost VARCHAR(20) NOT NULL,
    type_kost VARCHAR(100) NOT NULL,
    ukuran VARCHAR(20) NOT NULL,
    alamat_kost TEXT NOT NULL,
    harga_kost DECIMAL(10,2) NOT NULL,
    fasilitas VARCHAR(100) NOT NULL,
    status_kost ENUM('tersedia', 'terisi') NOT NULL,  -- Using ENUM for status_kost
    gambar BLOB NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create sewa table
CREATE TABLE sewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_kost INT,
    tanggal_sewa DATE NOT NULL,
    tanggal_akhir DATE NOT NULL,
    status_sewa ENUM('aktif','pending', 'tidak aktif') NOT NULL,  -- Using ENUM for status_sewa
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_kost) REFERENCES kosts(id)
);

-- Create ulasan table
CREATE TABLE ulasan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT,
    name VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    komen TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sewa) REFERENCES sewa(id)
);

-- create replies table
CREATE TABLE replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT,
    reply TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);
-- Create pembayaran table
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT,
    tanggal_pembayaran DATE NOT NULL,
    jumlah_pembayaran DECIMAL(10,2) NOT NULL,
    status_pembayaran ENUM('lunas', 'belum lunas') NOT NULL,  -- Using ENUM for status_pembayaran
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_payment_method INT(11) NOT NULL,
    FOREIGN KEY (id_sewa) REFERENCES sewa(id)
);

-- Create payment_methods table
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (id) REFERENCES pembayaran(id_payment_method)
);

-- Example SQL to create a transactions table
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,  -- Menghubungkan ke tabel 'users'
    no_kost INT,  -- Menghubungkan ke tabel 'kosts'
    username VARCHAR(50),
    img BLOB, --
    harga DECIMAL(10, 2),
    durasi INT,
    total_harga DECIMAL(10, 2),
    email VARCHAR(100),
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (no_kost) REFERENCES kosts(id)
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,  -- Linked to users table
    no_kost INT,  -- Linked to kosts table
    amount DECIMAL(10, 2),
    due_date DATE,
    status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (no_kost) REFERENCES kosts(id)
);

