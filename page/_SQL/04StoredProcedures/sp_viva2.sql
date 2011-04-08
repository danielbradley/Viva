CREATE FUNCTION Check_Password( $Uid INT(11), $Password CHAR(99) )
RETURNS BOOLEAN
BEGIN

DECLARE _ret         BOOLEAN  DEFAULT False;
DECLARE _salt        CHAR(16) DEFAULT 0;
DECLARE _phash1      CHAR(16) DEFAULT '';
DECLARE _phash2      CHAR(16) DEFAULT '';
DECLARE _combined    CHAR(99) DEFAULT '';
DECLARE _user_status CHAR(10) DEFAULT '';

SELECT user_status INTO _user_status FROM users WHERE uid=$Uid;

IF "ACTIVE" = _user_status THEN

    SELECT user_salt     INTO _salt     FROM users WHERE uid=$Uid;
    SELECT password_hash INTO _phash1   FROM users WHERE uid=$Uid;

    SET _combined  = MD5( concat($Password,_salt) );
    SET _phash2    = _combined;

    IF _phash1=_phash2 THEN
        SET _ret=True;
    END IF;
END IF;

return _ret;

END;
;
