DROP TABLE IF EXISTS `Musique`;
CREATE TABLE Musique (
  idM INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  titre VARCHAR(100) NOT NULL,
  groupe VARCHAR(100) NOT NULL,
  album VARCHAR(100) NOT NULL
);

INSERT INTO Musique (idM, titre, groupe,album) VALUES
(1, 'High Hopes', 'Panic at the disco','Pray for the Wicked'),
(2, 'We Didnt start the fire ','Billy Joel','Storm Front'),
(3, 'Lush Life', 'Zara Larsson','So Good'),
(4, 'Zero', 'Imagine Dragons','Origins(Deluxe)'),
(5, 'Sharks', 'Imagine Dragons','Mercury - Acts 1 & 2'),
(6, 'Duality', 'Set It Off','Duality'),
(7, 'Paint It Black', 'Rolling Stones','The Rolling Stones Singles Collection'),
(8, 'iAAM', 'Coldplay','Moon Music'),
(9, 'I dont care', 'Fall Out Boy','Folie à Deux'),
(10, 'Spot a fake', 'Ava Max','Spot a fake'),
(11, 'Stargazing', 'Myles Smith','Stargazing'),
(12, 'Coral Crown', 'Darren Korb, Erin Yvette, Ashley Barret, Jude Alice Lee','Coral Crown'),
(13, 'Fel Invincible', 'Skillet','Unleashed'),
(14, 'Set Fire to the Rain', 'Adele','21'),
(15, 'Shadow', 'Livingston','A Hometown Odyssey'),
(16, 'Echoes', 'Tom Walker','I AM'),
(17, 'Emptiness Machine', 'Linkin Park','From Zero'),
(18, 'Kill the Director', 'The Wombats','Proudly Present'),
(19, 'Choose Your Fighter', 'Ava Max','Barbie The Album'),
(20, 'Illusion', 'Dua Lipa','Radical Optimism'),
(21, 'RUNAWAY', 'OneRepublic','RUNAWAY'),
(22, 'Black Summer', 'Red Hot Chili Peppers','Black Summer'),
(23, 'West Coast', 'OneRepublic','West Coast'),
(24, 'Hotel California', 'Eagles','Hotel California'),
(25, 'Viva La Vide', 'Coldplay','Viva La Vida or Death and All His Friends'),
(26, 'Icarus', 'Bastille','All this Bad Blood');
