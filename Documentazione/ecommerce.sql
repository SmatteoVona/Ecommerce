DROP database ecommerce;
CREATE DATABASE if NOT EXISTS  ecommerce;
USE ecommerce;

CREATE TABLE IF NOT EXISTS cliente (
ID INT PRIMARY KEY AUTO_INCREMENT,
nome VARCHAR(255) NOT NULL,
cognome VARCHAR(255) NOT NULL,
mail VARCHAR(255) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL );


CREATE TABLE if NOT EXISTS accessorio (
ID INT PRIMARY KEY AUTO_INCREMENT,
nome VARCHAR(255) NOT NULL,
descrizione VARCHAR(1000),
prezzo DECIMAL(10, 2) NOT NULL );

CREATE TABLE if NOT EXISTS categoria(
ID INT PRIMARY KEY AUTO_INCREMENT,
nome VARCHAR(255) NOT NULL,
descrizione VARCHAR(1000));

CREATE TABLE if NOT EXISTS prodotto(
ID INT PRIMARY KEY AUTO_INCREMENT,
nome VARCHAR(255) NOT NULL , 
descrizione VARCHAR(1000),
prezzo FLOAT NOT NULL ,
percorso_immagine VARCHAR(255),
ID_categoria INT,
FOREIGN KEY (ID_categoria) REFERENCES categoria(ID));

CREATE TABLE if NOT EXISTS carrello(
ID INT PRIMARY KEY AUTO_INCREMENT,
ID_cliente INT NOT NULL,
FOREIGN KEY (ID_cliente) REFERENCES cliente(ID));

CREATE TABLE if NOT EXISTS ordine(
ID INT PRIMARY KEY AUTO_INCREMENT,
indirizzo VARCHAR(255) NOT NULL,
stato VARCHAR(255) NOT NULL,
ID_carrello INT NOT NULL ,
FOREIGN KEY (ID_carrello) REFERENCES carrello(ID));

CREATE TABLE if NOT EXISTS lista_accessori(
ID INT PRIMARY KEY AUTO_INCREMENT,
ID_prodotto INT NOT NULL ,
ID_accessorio INT NOT NULL ,
FOREIGN KEY (ID_prodotto) REFERENCES prodotto(ID),
FOREIGN KEY (ID_accessorio) REFERENCES accessorio(ID));

CREATE TABLE if NOT EXISTS prodotti_carrello(
ID INT PRIMARY KEY AUTO_INCREMENT,
ID_carrello INT NOT NULL ,
ID_prodotto INT NOT NULL ,
ID_accessorio INT,
FOREIGN KEY (ID_carrello) REFERENCES carrello(ID),
FOREIGN KEY (ID_prodotto) REFERENCES prodotto(ID),
FOREIGN KEY (ID_accessorio) REFERENCES accessorio(ID));

CREATE TABLE if NOT EXISTS dettaglio_ordine(
ID INT PRIMARY KEY AUTO_INCREMENT,
ID_carrello INT NOT NULL ,
ID_prodotto INT NOT NULL ,
ID_accessorio INT,
FOREIGN KEY (ID_carrello) REFERENCES carrello(ID),
FOREIGN KEY (ID_prodotto) REFERENCES prodotto(ID),
FOREIGN KEY (ID_accessorio) REFERENCES accessorio(ID));


INSERT INTO cliente (nome, cognome, mail, password) VALUES
('Mario', 'Rossi', 'mario.rossi@example.com', 'password123'),
('Luca', 'Bianchi', 'luca.bianchi@example.com', 'password456'),
('Fabrizio', 'Verdi', 'fabrizio.verdi@example.com', 'abcde'),
('Chiara', 'Neri', 'chiara.neri@example.com', '12345');


INSERT INTO accessorio (nome, descrizione, prezzo) VALUES
('Cuscino decorativo', 'Cuscino in cotone con motivi geometrici', 25.99),
('Lampada da tavolo', 'Lampada in stile moderno, perfetta per il soggiorno', 49.99),
('Poggia bicchieri', 'Poggia bicchieri in plastica dura, per evitare di sporcare', 9.99);


INSERT INTO categoria (nome, descrizione) VALUES
('Soggiorno', 'Mobili e accessori per il soggiorno'),
('Camera', 'Tutto il necessario per arredare la tua camera da letto'),
('Ingresso', 'Arredi e complementi per dare il benvenuto in casa tua'),
('Terrazza', 'Arredamento e decorazioni per esterni');


INSERT INTO prodotto (nome, descrizione, prezzo, ID_categoria, percorso_immagine) VALUES
('Tavolino da caffè', 'Tavolino in legno massello con ripiano in vetro', 199.99, (SELECT ID FROM categoria WHERE nome='Soggiorno'), 'immagini/tavolino1.png'),
('Divano angolare', 'Divano angolare in tessuto grigio, 5 posti', 899.99, (SELECT ID FROM categoria WHERE nome='Soggiorno'), 'immagini/divano1.jpg'),
('Poltrona piegevole', 'Morbida poltrona reclinabile', 299.99, (SELECT ID FROM categoria WHERE nome='Terrazza'), 'immagini/poltrona.jpg'),
('Lampada da terra', 'Lampada da terra moderna in metallo nero', 89.99, (SELECT ID FROM categoria WHERE nome='Soggiorno'), 'immagini/lampada1.jpg'),
('Armadio a due ante', 'Armadio spazioso in legno chiaro', 599.99, (SELECT ID FROM categoria WHERE nome='Camera'), 'immagini/armadio.jpg'),
('Comodino in legno', 'Comodino in legno con due cassetti', 129.99, (SELECT ID FROM categoria WHERE nome='Camera'), 'immagini/comodino.jpg'),
('Specchiera da ingresso', 'Specchiera con cornice in legno e mensola', 79.99, (SELECT ID FROM categoria WHERE nome='Ingresso'), 'immagini/specchiera.jpg'),
('Appendiabiti da parete', 'Appendiabiti in metallo con design moderno', 49.99, (SELECT ID FROM categoria WHERE nome='Ingresso'), 'immagini/appendiabiti.jpg'),
('Letto matrimoniale', 'Letto matrimoniale con contenitore, in legno', 499.99, (SELECT ID FROM categoria WHERE nome='Camera'), 'immagini/letto.jpg'),
('Set di sedie da giardino', 'Set di 4 sedie in metallo pieghevoli', 149.99, (SELECT ID FROM categoria WHERE nome='Terrazza'), 'immagini/sedie.jpg'),
('Ombrellone da giardino', 'Ombrellone grande resistente ai raggi UV', 99.99, (SELECT ID FROM categoria WHERE nome='Terrazza'), 'immagini/ombrellone.jpg');


INSERT INTO carrello (ID_cliente) VALUES
((SELECT ID FROM cliente WHERE mail='mario.rossi@example.com')),
((SELECT ID FROM cliente WHERE mail='luca.bianchi@example.com'));


INSERT INTO ordine (indirizzo, stato, ID_carrello) VALUES
('Via Roma 1, Milano', 'Spedito', (SELECT ID FROM carrello WHERE ID_cliente=(SELECT ID FROM cliente WHERE mail='mario.rossi@example.com'))),
('Via Milano 2, Roma', 'In preparazione', (SELECT ID FROM carrello WHERE ID_cliente=(SELECT ID FROM cliente WHERE mail='luca.bianchi@example.com')));


INSERT INTO lista_accessori (ID_prodotto, ID_accessorio) VALUES
((SELECT ID FROM prodotto WHERE nome='Divano angolare'), (SELECT ID FROM accessorio WHERE nome='Cuscino decorativo')),
((SELECT ID FROM prodotto WHERE nome='Letto matrimoniale'), (SELECT ID FROM accessorio WHERE nome='Lampada da tavolo'));


INSERT INTO prodotti_carrello (ID_carrello, ID_prodotto, ID_accessorio) VALUES
((SELECT ID FROM carrello WHERE ID_cliente=(SELECT ID FROM cliente WHERE mail='mario.rossi@example.com')), (SELECT ID FROM prodotto WHERE nome='Divano angolare'),2),
((SELECT ID FROM carrello WHERE ID_cliente=(SELECT ID FROM cliente WHERE mail='luca.bianchi@example.com')), (SELECT ID FROM prodotto WHERE nome='Letto matrimoniale'),NULL);






