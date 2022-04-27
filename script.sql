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
    user_creator_id INT NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    FOREIGN KEY (user_creator_id) REFERENCES user(id)
);

CREATE TABLE user_community (
    id_user INT NOT NULL,
    id_community INT NOT NULL,
    is_admin BOOL DEFAULT false,
    invitation_accepted BOOL DEFAULT false,
    write_permission BOOL DEFAULT true,
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

CREATE TABLE user_like (
    id_user INT NOT NULL,
    id_publication INT NOT NULL,
    PRIMARY KEY (id_user, id_publication),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_publication) REFERENCES publication(id)
);

CREATE TABLE vote (
    id INT NOT NULL AUTO_INCREMENT,
    name_vote VARCHAR(50) NOT NULL,
    description_vote VARCHAR(300),
    positive_votes INT DEFAULT 0,
    negative_votes INT DEFAULT 0,
    date_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    date_end TIMESTAMP NULL,
    user_creator INT,
    id_community INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_creator) REFERENCES user(id),
    FOREIGN KEY (id_community) REFERENCES community(id)
);

CREATE TABLE user_vote (
    id_user INT NOT NULL,
    id_vote INT NOT NULL,
    vote_value BOOL NOT NULL,
    PRIMARY KEY (id_user, id_vote),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_vote) REFERENCES vote(id)
);

CREATE TABLE session_token (
    token VARCHAR(100) NOT NULL,
    date_token TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    id_user INT NOT NULL,
    PRIMARY KEY (token),
    FOREIGN KEY (id_user) REFERENCES user(id),
    UNIQUE (id_user)
);

CREATE UNIQUE INDEX login_mail ON user(mail);

-- User

delimiter $$
CREATE TRIGGER user_community_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM user_community WHERE id_user=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER user_vote_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM user_vote WHERE id_user=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER user_like_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM user_like WHERE id_user=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER publication_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM publication WHERE id_user=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER session_token_delete_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    DELETE FROM session_token WHERE id_user=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER vote_update_when_user_delete 
    BEFORE DELETE  
    ON user 
    FOR EACH ROW
BEGIN
    UPDATE vote SET user_creator=null WHERE user_creator=OLD.id;
END; $$
delimiter ;

-- Community

delimiter $$
CREATE TRIGGER user_community_insert_when_community_insert
    AFTER INSERT
    ON community
    FOR EACH ROW
BEGIN
    INSERT INTO user_community VALUES (NEW.user_creator_id, NEW.id, true, true, true);
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER user_community_delete_when_community_remove
    BEFORE DELETE
    ON community
    FOR EACH ROW
BEGIN
    DELETE FROM user_community WHERE id_community=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER vote_delete_when_community_remove
    BEFORE DELETE 
    ON community 
    FOR EACH ROW
BEGIN
    DELETE FROM vote WHERE id_community=OLD.id;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER publication_delete_when_community_remove
    BEFORE DELETE 
    ON community 
    FOR EACH ROW
BEGIN
    DELETE FROM publication WHERE id_community=OLD.id;
END; $$
delimiter ;

-- Vote

delimiter $$
CREATE TRIGGER vote_update_when_user_vote_insert
    AFTER INSERT
    ON user_vote
    FOR EACH ROW
BEGIN
    IF NEW.vote_value THEN
        UPDATE vote SET positive_votes=(SELECT SUM(positive_votes + 1) FROM vote WHERE id=NEW.id_vote) WHERE id=NEW.id_vote;
    ELSE
        UPDATE vote SET negative_votes=(SELECT SUM(negative_votes + 1) FROM vote WHERE id=NEW.id_vote) WHERE id=NEW.id_vote;
    END IF;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER user_vote_delete_when_vote_delete 
    BEFORE DELETE  
    ON vote 
    FOR EACH ROW
BEGIN
    DELETE FROM user_vote WHERE id_vote=OLD.id;
END; $$
delimiter ;

-- Publication

delimiter $$
CREATE TRIGGER publication_update_when_user_like_insert
    AFTER INSERT
    ON user_like
    FOR EACH ROW
BEGIN
    UPDATE publication SET likes=(SELECT SUM(likes + 1) FROM publication WHERE id=NEW.id_publication) WHERE id=NEW.id_publication;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER publication_update_when_user_like_remove
    BEFORE DELETE
    ON user_like
    FOR EACH ROW
BEGIN
    UPDATE publication SET likes=(SELECT SUM(likes - 1) FROM publication WHERE id=OLD.id_publication) WHERE id=OLD.id_publication;
END; $$
delimiter ;

delimiter $$
CREATE TRIGGER user_like_delete_when_publication_delete
    BEFORE DELETE
    ON publication
    FOR EACH ROW
BEGIN
    DELETE FROM user_like WHERE id_publication=OLD.id;
END; $$
delimiter ;




-- Inserciones de prueba

INSERT INTO user (id, name_user, last_name, mail, pass_user) VALUES (1,'user','last','user@gmail.com','$2y$10$ANn0hX3ENkfaFiFwDM9GgOEyQ58BwpVFEpJ63X2Of98456M6.ucja');
INSERT INTO user (id, name_user, last_name, mail, pass_user) VALUES (2,'admin','lasted','admin@gmail.com','$2y$10$An0hX3ENkfaFiFwDM9GgOEyQ58BwpVFEpJ63X2Of98456M6.ucja');


INSERT INTO community (id, name_community,user_creator_id) VALUES (1, 'community', 1);
INSERT INTO community (id, name_community,user_creator_id) VALUES (2, 'community2', 1);

INSERT INTO user_community VALUES (2,1,false,true,true);

INSERT INTO session_token VALUES ('7c1ef4a0256611-1',null,1);
INSERT INTO session_token VALUES ('7c1ef4a0256611-2',null,2);