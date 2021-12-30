CREATE DATABASE readme
  DEFAULT CHARACTER SET UTF8
  DEFAULT COLLATE utf8_general_ci;
USE readme;
CREATE TABLE user
(
  id       int AUTO_INCREMENT PRIMARY KEY,
  reg_date datetime,
  email    varchar(128),
  login    varchar(128),
  password varchar(64),
  avatar   text
);
CREATE TABLE content_type
(
  id        int AUTO_INCREMENT PRIMARY KEY,
  type_name varchar(32),
  icon_name varchar(32)
);
CREATE TABLE post
(
  id                int AUTO_INCREMENT PRIMARY KEY,
  create_date       datetime,
  header            varchar(255),
  text_content      text,
  author_copy_right varchar(255),
  media             text,
  views_number      int,
  user_id           int,
  content_type_id   int,
  repost            bool,
  originalPostId    int,
  FOREIGN KEY (`user_id`) REFERENCES user (`id`),
  FOREIGN KEY (`content_type_id`) REFERENCES content_type (`id`)

);
CREATE TABLE comment
(
  id          int AUTO_INCREMENT PRIMARY KEY,
  create_date datetime,
  content     text,
  user_id     int,
  post_id     int,
  FOREIGN KEY (`user_id`) REFERENCES user (`id`),
  FOREIGN KEY (`post_id`) REFERENCES post (`id`)
);
CREATE TABLE like_count
(
  id      int AUTO_INCREMENT PRIMARY KEY,
  user_id int,
  post_id int,
  FOREIGN KEY (`user_id`) REFERENCES user (`id`),
  FOREIGN KEY (`post_id`) REFERENCES post (`id`)
);

CREATE TABLE subscribe
(
  id                int AUTO_INCREMENT PRIMARY KEY,
  user_subscribe_id int,
  user_author_id    int,
  FOREIGN KEY (`user_subscribe_id`) REFERENCES user (`id`),
  FOREIGN KEY (`user_author_id`) REFERENCES user (`id`)

);

CREATE TABLE massage
(
  id               int AUTO_INCREMENT PRIMARY KEY,
  create_date      datetime,
  content          text,
  user_sender_id   int,
  user_receiver_id int,
  FOREIGN KEY (`user_sender_id`) REFERENCES user (`id`),
  FOREIGN KEY (`user_receiver_id`) REFERENCES user (`id`)
);

CREATE TABLE hashtag
(
  id           int AUTO_INCREMENT PRIMARY KEY,
  hashtag_name varchar(64)
);

CREATE TABLE hashtag_post
(
  id      int AUTO_INCREMENT PRIMARY KEY,
  hashtag int,
  post    int,
  FOREIGN KEY (`hashtag`) REFERENCES hashtag (`id`),
  FOREIGN KEY (`post`) REFERENCES post (`id`)

);

CREATE UNIQUE INDEX email ON user (email);
CREATE INDEX login ON user (login);
CREATE INDEX reg_date ON user (reg_date);
CREATE INDEX type_name ON content_type (type_name);
CREATE INDEX create_date ON post (create_date);
CREATE INDEX header ON post (header);
CREATE INDEX create_date ON massage (create_date);
CREATE INDEX hashtag_name ON hashtag (hashtag_name);
CREATE FULLTEXT INDEX post_ft_search ON post (header, text_content);
CREATE FULLTEXT INDEX post_ft_search ON hashtag (hashtag_name);



