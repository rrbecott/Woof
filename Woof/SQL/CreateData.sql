INSERT INTO Accounts (token, email, pass, name, breed, bio, age, picture) Values
('a', "jwalker@mtu.edu", "password", "James", "Corgi", "I am Walker", 18, NULL),
('a', "knalker@mtu.edu", "password", "Kames", "Husky", "I am Nalker", 18, NULL),
('a', "bsqualker@mtu.edu", "password", "Bames", "German Shephard", "I am Squalker", 18, NULL),
('a', "rfalker@mtu.edu", "password", "Rames", "Terrier", "I am Falker", 19, NULL);

CALL createAccount('a', 'tyalker@mtu.edu', 'password');
CALL modifyProfile(5, 'Tames', NULL, 'I am Yalker', 45, NULL);

call swipe(1, 2, TRUE);
call swipe(1, 3, TRUE);
#call swipe(1, 4, TRUE);
#call swipe(1, 5, TRUE);
#call swipe(5, 1, TRUE);
#call swipe(4, 1, TRUE);
call swipe(3, 1, TRUE);
call swipe(2, 1, TRUE);

select * from Accounts;