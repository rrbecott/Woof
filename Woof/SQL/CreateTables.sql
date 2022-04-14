CREATE TABLE Accounts (
	accountNum int primary key auto_increment,
    token char(255) not null,
    email varchar(40) unique not null,
    pass char(255) not null,
    name varchar(40),
    breed varchar(40),
    bio varchar(500),
    age int,
    picture blob
);

CREATE TABLE RightSwipes (
	swiper int not null,
    swiped int not null,
    foreign key (swiper) references Accounts(accountNum),
    foreign key (swiped) references Accounts(accountNum),
    UNIQUE KEY (swiper, swiped)
);

CREATE TABLE Matches(
	acc1 int not null,
    acc2 int not null,
    foreign key (acc1) references Accounts(accountNum),
    foreign key (acc2) references Accounts(accountNum),
    UNIQUE KEY(acc1, acc2)
);