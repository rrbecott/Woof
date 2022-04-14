//Check Email

BEGIN
	DECLARE val tinyint(1);
	
    SELECT EXISTS(SELECT * from Accounts where email = e) into val;
    RETURN val;
END

//Login

BEGIN
	DECLARE val tinyint(1);
	
    SELECT EXISTS(SELECT * from Accounts where email = e AND pass = PASSWORD (passw)) into val;
    RETURN val;
END

//Next Match

BEGIN
	DECLARE onum INT(11);
	SELECT accountNum INTO onum
    FROM Accounts
    WHERE accountNum != acNum AND accountNum NOT IN (
		SELECT swiped
        FROM RightSwipes
        WHERE swiper = acNum
    )
    ORDER BY RAND()
    LIMIT 1;
    RETURN onum;
END
