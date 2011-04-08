CREATE PROCEDURE Activation_Generate
(
  $Username   CHAR(99),
  $Type       CHAR(30)
)
BEGIN

DECLARE _uid        INT(11);
DECLARE _token1 VARCHAR(32);
DECLARE _token2 VARCHAR(32);
DECLARE _token  VARCHAR(64);

SELECT MD5(RAND()) INTO _token1;
SELECT MD5(RAND()) INTO _token2;
SELECT CONCAT( _token1, _token2 ) INTO _token;

SELECT uid INTO _uid FROM users WHERE username=$Username;

IF "" != _uid THEN
    REPLACE INTO activations VALUES ( _uid, NOW(), $Type, _token );
    SELECT * FROM activations WHERE uid=_uid;
END IF;

END
;
CREATE PROCEDURE Change_Password
(
  $Token            CHAR(64),
  $New_password     CHAR(99)
)
BEGIN

DECLARE _uid         INT(11);
DECLARE _username   CHAR(99);

DECLARE _id      INT(11) DEFAULT 0;
DECLARE _salt    INT(11) DEFAULT 0;
DECLARE _uhash  CHAR(8)  DEFAULT '';
DECLARE _phash  CHAR(16) DEFAULT '';

DELETE FROM activations WHERE NOW() > timestamp + INTERVAL 1 DAY;

SELECT uid INTO _uid FROM activations WHERE token=$Token AND type='reset_password' AND NOW() < timestamp + INTERVAL 1 DAY;

IF _uid != 0 THEN

    SELECT username INTO _username FROM users WHERE uid=_uid;

    SET _salt  = RAND() * 1000;
    SET _uhash = MD5( concat(_username,_salt) );
    SET _phash = MD5( concat($New_password,_salt) );

    UPDATE users
    SET user_salt=_salt, user_hash=_uhash, password_hash=_phash
    WHERE uid=_uid;

    DELETE FROM activations WHERE token=$Token;
END IF;

END
;
CREATE PROCEDURE Activate_Account
(
  $Token            CHAR(64)
)
BEGIN

SELECT uid INTO @uid FROM activations WHERE token=$Token;

IF @uid != 0 THEN
    UPDATE users SET user_status='ACTIVE' WHERE uid=@uid;
    DELETE FROM activations WHERE token=$Token;
END IF;

END
;
CREATE PROCEDURE Menus_Retrieve_All()
BEGIN

SELECT * FROM menus ORDER BY menu_name;

END
;
CREATE PROCEDURE Menus_Retrieve( $Menu_name CHAR(50) )
BEGIN

SELECT * FROM
    ( SELECT * FROM view_menus ORDER BY menu_name, position, page_name, timestamp DESC ) AS menus
WHERE menu_name=$Menu_name GROUP BY page_name ORDER BY menu_name, position, page_heading;

END
;
CREATE PROCEDURE Menus_Retrieve_ID( $Menu_name CHAR(50) )
BEGIN

SELECT * FROM menus WHERE menu_name=$Menu_name LIMIT 1;

END
;
CREATE PROCEDURE Pages_Retrieve( $Page_name CHAR(50) )
BEGIN

SELECT * FROM pages WHERE page_name=$Page_name AND publish='1' ORDER BY timestamp DESC LIMIT 1;

END
;
CREATE PROCEDURE Pages_Retrieve_One(
  $Page_name CHAR(50),
  $PAGE_ID    INT(11)
)
BEGIN

IF $PAGE_ID THEN
    SELECT * FROM pages WHERE page_name=$Page_name AND PAGE_ID=$PAGE_ID ORDER BY timestamp DESC LIMIT 1;
ELSE
    SELECT * FROM pages WHERE page_name=$Page_name ORDER BY timestamp DESC LIMIT 1;
END IF;

END
;
CREATE PROCEDURE Pages_Retrieve_Timestamps( $Page_name CHAR(50) )
BEGIN

SELECT PAGE_ID, timestamp, publish FROM pages WHERE page_name=$Page_name ORDER BY timestamp DESC;

END
;
CREATE PROCEDURE Page_Save
(
$PAGE_ID        INT(11),
$Publish           BOOL,
$Page_template     CHAR(50),
$Page_name         CHAR(50),
$Page_heading      CHAR(50),
$Page_title        CHAR(255),
$Page_url          CHAR(99),
$Page_keywords     CHAR(255),
$Page_description  TEXT,
$Page_content      TEXT,
$Menu_id            INT(11),
$Position           INT(11)
)
BEGIN

INSERT INTO pages VALUES
( '0', NOW(), $Publish, $Page_template, $Page_name, $Page_heading, $Page_title, $Page_url, $Page_keywords, $Page_description, $Page_content, $Menu_id, $Position );

END
;
