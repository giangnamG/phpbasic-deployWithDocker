FROM mariadb:10.4.12
RUN mysql -p ngn@ngn

RUN ALTER USER 'ngn'@'%' IDENTIFIED WITH mysql_native_password BY 'ngn@ngn'; \
    FLUSH PRIVILEGES; \
    create table login-info (id int NOT NULL, username varchar(100) NOT NULL, password varchar(200) NOT NULL,email varchar(100) NOT NULL,phone varchar(20) NOT NULL,active int default 0,permission varchar(20) default 'viewer',UNIQUE(username,email,phone)); \
    exit;