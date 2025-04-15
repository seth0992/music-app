CREATE DATABASE bd_musichub;

USE bd_musichub;

CREATE TABLE tbl_songs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    review TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    created_at DATETIME NOT NULL,
    user_id INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Crear tabla de usuarios
CREATE TABLE tbl_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default_profile.jpg',
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Añadir usuario por defecto (password: 123456 - hash MD5)
INSERT INTO tbl_users (username, password, full_name, email, profile_image, created_at) 
VALUES ('admin', 'e10adc3949ba59abbe56e057f20f883e', 'Administrador', 'admin@musicapp.com', 'default_profile.jpg', NOW());


-- Añadir la restricción de clave foránea
ALTER TABLE tbl_songs
ADD CONSTRAINT fk_songs_users
FOREIGN KEY (user_id) REFERENCES tbl_users(id)
ON DELETE CASCADE ON UPDATE CASCADE;