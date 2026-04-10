CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone  VARCHAR(11),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user', 'super_admin') default 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE dealers(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(150),
    contact_phone INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE cars(
    id INT AUTO_INCREMENT PRIMARY KEY,
    dealer_id INT,
    brand VARCHAR(50),
    model VARCHAR(50),
    year YEAR,
    image TEXT.
    price DECIMAL(12,2),
    mileage INT,
    fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid'),
    transmission ENUM('manual','automatic'),
    description TEXT,
    status ENUM('available', 'reserved', 'sold') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (dealer_id) REFERENCES dealers(id)
)ENGINE=InnoDB;

CREATE TABLE car_images( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_path VARCHAR(225) NOT NULL,
    FOREIGN KEY(car_id) REFERENCES cars(id) ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE bookings(
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   car_id INT NOT NULL,
   booking_date DATE NOT NULL,
   booking_type ENUM ('test_drive', 'purchase') NOT NULL,
   status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending',
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

   FOREIGN KEY(user_id) REFERENCES users(id),
   FOREIGN KEY(car_id) REFERENCES cars(id)
)ENGINE=InnoDB;

CREATE TABLE payments(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('card', 'bank_transfer', 'cash'),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
)ENGINE=InnoDB;

CREATE TABLE reviews(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_id INT,    
    rating INT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ,   
    FOREIGN KEY (car_id) REFERENCES cars(id)   
)ENGINE=InnoDB;
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,

    UNIQUE KEY unique_wishlist (user_id, car_id)
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_user_car (user_id, car_id),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',

    -- customer details snapshot
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    car_id INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

ALTER TABLE bookings 
ADD COLUMN full_name VARCHAR(255),
ADD COLUMN email VARCHAR(255),
ADD COLUMN phone VARCHAR(20),
ADD COLUMN booking_time TIME,
ADD COLUMN message TEXT;

ALTER TABLE orders 
ADD COLUMN payment_ref VARCHAR(100) NULL;
