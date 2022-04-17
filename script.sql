DROP DATABASE IF EXISTS happyneighbor;
CREATE DATABASE happyneighbor DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
CREATE USER IF NOT EXISTS 'happyneighbor'@'localhost' IDENTIFIED BY '5GLJyHW8q6Oa';
GRANT ALL ON happyneighbor.* TO 'happyneighbor'@'localhost';

USE happyneighbor;

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    name_user VARCHAR(50) NOT NULL,
    last_name VARCHAR(80),
    mail VARCHAR(80) NOT NULL,
    pass_user VARCHAR(100) NOT NULL,
    profile_picture BLOB,
    biography VARCHAR(300),
    PRIMARY KEY (id),
    UNIQUE (mail)
);

CREATE TABLE community (
    id INT NOT NULL AUTO_INCREMENT,
    name_community VARCHAR(50) NOT NULL,
    description_community VARCHAR(300),
    total_money FLOAT(9,2),
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
);

CREATE TABLE user_community (
    id_user INT NOT NULL,
    id_community INT NOT NULL,
    is_admin BOOL DEFAULT false,
    PRIMARY KEY (id_user, id_community),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_community) REFERENCES community(id)
);

CREATE TABLE publication (
    id INT NOT NULL AUTO_INCREMENT,
    text_publication VARCHAR(300) NOT NULL,
    likes INT DEFAULT 0,
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    comment_to INT NULL,
    id_user INT NOT NULL,
    id_community INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (comment_to) REFERENCES publication(id),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_community) REFERENCES community(id)
);

CREATE TABLE vote (
    id INT NOT NULL AUTO_INCREMENT,
    name_vote VARCHAR(50) NOT NULL,
    description_vote VARCHAR(300) NOT NULL,
    positive_votes INT DEFAULT 0,
    negative_votes INT DEFAULT 0,
    date_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    date_end TIMESTAMP NULL,
    user_creator INT NOT NULL,
    id_community INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_creator) REFERENCES user(id),
    FOREIGN KEY (id_community) REFERENCES community(id)
);

CREATE TABLE user_vote (
    id_user INT NOT NULL,
    id_vote INT NOT NULL,
    PRIMARY KEY (id_user, id_vote),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_vote) REFERENCES vote(id)
);

CREATE TABLE cash_flow_community (
    id INT NOT NULL AUTO_INCREMENT,
    amount FLOAT(9,2) NOT NULL,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    name_transaction VARCHAR(50),
    concept VARCHAR(300),
    id_community INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_community) REFERENCES community(id)
);

CREATE UNIQUE INDEX login_mail ON user(mail);

delimiter $$
CREATE TRIGGER user_community_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM user_community WHERE id_user=OLD.id;
END; $$
delimiter ;

-- Inserciones de prueba

INSERT INTO user (id, name_user, last_name, mail, pass_user) VALUES (1,'user','last','user@gmail.com','$2y$10$ANn0hX3ENkfaFiFwDM9GgOEyQ58BwpVFEpJ63X2Of98456M6.ucja');

INSERT INTO community (id, name_community,total_money) VALUES (1, 'community', 10.2);
INSERT INTO community (id, name_community,total_money) VALUES (2, 'community2', 11.2);

INSERT INTO user_community (id_user, id_community, is_admin) VALUES (1,1,false);
INSERT INTO user_community (id_user, id_community, is_admin) VALUES (1,2,false);