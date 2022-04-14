CALL nextMatch(1, @num);
CALL getProfile(@num, @name, @breed, @bio, @age, @picture);
SELECT @name, @breed, @bio, @age, @picture;