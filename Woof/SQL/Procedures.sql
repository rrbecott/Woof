delimiter //

CREATE PROCEDURE nextMatch(IN acNum int, OUT onum int)
BEGIN
	SELECT accountNum INTO onum
    FROM Accounts
    WHERE accountNum != acNum AND accountNum NOT IN (
		SELECT swiped
        FROM RightSwipes
        WHERE swiper = acNum
    )
    ORDER BY RAND()
    LIMIT 1;
END//

CREATE PROCEDURE getProfile(IN acNum int, OUT oname varchar(40), OUT obreed varchar(40), OUT obio varchar(500), OUT oage int, OUT opicture blob)
BEGIN
    SELECT name, breed, bio, age, picture INTO oname, obreed, obio, oage, opicture
    FROM Accounts
    WHERE accountNum = acNum;
END//

CREATE PROCEDURE createAccount(IN tok char(255), IN email varchar(40), IN password char(255))
BEGIN
	INSERT IGNORE INTO Accounts (token, email, pass)
	value (tok, email, PASSWORD(password));
END//

CREATE PROCEDURE modifyProfile(IN acNum int, IN name varchar(40), IN breed varchar(40), IN bio varchar(500), IN age int, IN picture blob)
BEGIN
    IF name is not null THEN
		UPDATE Accounts
		SET Accounts.name = name
        WHERE acNum = Accounts.accountNum;
	END IF;
    IF breed is not null THEN
		UPDATE Accounts
        SET Accounts.breed = breed
        WHERE acNum = Accounts.accountNum;
	END IF;
    IF bio is not null THEN
		UPDATE Accounts
        SET Accounts.bio = bio
        WHERE acNum = Accounts.accountNum;
	END IF;
    IF age is not null THEN
		UPDATE Accounts
        SET Accounts.age = age
        WHERE acNum = Accounts.accountNum;
    END IF;
    IF picture is not null THEN
		UPDATE Accounts
        SET Accounts.picture = picture
        WHERE acNum = Accounts.accountNum;
    END IF;
END//

CREATE PROCEDURE swipe(IN swiper int, IN swiped int, IN swipedRight BOOL)
BEGIN
	IF swipedRight = 1 THEN
		INSERT INTO RightSwipes
        value (swiper, swiped);
        IF EXISTS ( SELECT *
			FROM RightSwipes
			WHERE RightSwipes.swiper = swiped AND RightSwipes.swiped = swiper)
		THEN
			INSERT INTO Matches
            value(swiper, swiped);
		END IF;
    END IF;
END//

CREATE PROCEDURE getMatches(IN acc int)
BEGIN
    SELECT picture, name, email, breed
    FROM Matches
    WHERE acc1=acc OR acc2=acc;
END//

CREATE FUNCTION login(e varchar(40), passw char(255))
RETURNS tinyint(1)
BEGIN
	DECLARE val tinyint(1);
	
    SELECT EXISTS(SELECT * from Accounts where email = e AND pass = passw) into val;
    RETURN val;
END//

delimiter ;
