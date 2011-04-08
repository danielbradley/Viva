CREATE TABLE menus (

MENU_ID                         INT(11)  NOT NULL AUTO_INCREMENT,
menu_name                   VARCHAR(50)  NOT NULL,

PRIMARY KEY (MENU_ID)

);
CREATE TABLE pages (

PAGE_ID                         INT(11)   NOT NULL AUTO_INCREMENT,
timestamp                   TIMESTAMP     NOT NULL,
publish                        BOOL       NOT NULL,
page_template               VARCHAR(50)   NOT NULL,
page_name                   VARCHAR(50)   NOT NULL,
page_heading                VARCHAR(50)   NOT NULL,
page_title                  VARCHAR(255)  NOT NULL,
page_url                    VARCHAR(99)   NOT NULL,
page_keywords               VARCHAR(255)  NOT NULL,
page_description               TEXT       NOT NULL,
page_content                   TEXT       NOT NULL,
MENU_ID                         INT(11)   NOT NULL,
position                        INT(11)   NOT NULL,
PRIMARY KEY (PAGE_ID)

);
