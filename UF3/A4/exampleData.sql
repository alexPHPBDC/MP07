DELETE FROM categories;
INSERT INTO categories (id, nom, iva,createdAt,updatedAt) 
VALUES 
("categoria1", 'Granos',"14","2023-02-15","2023-02-15"),
("categoria2", 'Verduras',"10","2023-02-15","2023-02-15"),
("categoria3", 'Frutas',"20","2023-02-15","2023-02-15"),
("categoria4", 'Lacteos',"11","2023-02-15","2023-02-15"),
("categoria5", 'Proteinas',"17","2023-02-15","2023-02-15");


DELETE FROM productes;
INSERT INTO productes (id, nom, tipus, preu, categoria,createdAt,updatedAt)
VALUES
("producte1", 'Garbanzo',"Aliment","1","Granos","2023-02-15","2023-02-15"),
("producte2", 'Lechuga',"Aliment","1","Verduras","2023-02-15","2023-02-15"),
("producte3", 'Manzana',"Aliment","1","Frutas","2023-02-15","2023-02-15"),
("producte4", 'Leche',"Aliment","1","Lacteos","2023-02-15","2023-02-15"),
("producte5", 'Pollo',"Aliment","1","Proteinas","2023-02-15","2023-02-15");


DELETE FROM maquines;
INSERT INTO maquines (id, municipi, adreca, createdAt,updatedAt)
VALUES
("maquina1", 'Barcelona',"Carrer de la Ciutat de Granada, 1","2023-02-15","2023-02-15"),
("maquina2", 'Barcelona',"Carrer de la Ciutat de Granada, 2","2023-02-15","2023-02-15"),
("maquina3", 'Barcelona',"Carrer de la Ciutat de Granada, 3","2023-02-15","2023-02-15"),
("maquina4", 'Barcelona',"Carrer de la Ciutat de Granada, 4","2023-02-15","2023-02-15"),
("maquina5", 'Barcelona',"Carrer de la Ciutat de Granada, 5","2023-02-15","2023-02-15");


DELETE FROM calaixos;
INSERT INTO calaixos (id, maquina, casella, createdAt,updatedAt)
VALUES
("calaix1", 'maquina1',"A1","2023-02-15","2023-02-15"),
("calaix2", 'maquina1',"A2","2023-02-15","2023-02-15"),
("calaix3", 'maquina1',"A3","2023-02-15","2023-02-15"),
("calaix4", 'maquina1',"A4","2023-02-15","2023-02-15"),
("calaix5", 'maquina1',"A5","2023-02-15","2023-02-15");


DELETE FROM estocs;
INSERT INTO estocs (id, producte, caducitat,dataVenda,ubicacio,createdAt,updatedAt)
VALUES
("estoc1", 'producte1',"2023-02-15","2023-02-15","calaix1","2023-02-15","2023-02-15"),
("estoc2", 'producte2',"2023-02-15","2023-02-15","calaix2","2023-02-15","2023-02-15"),
("estoc3", 'producte3',"2023-02-15","2023-02-15","calaix3","2023-02-15","2023-02-15"),
("estoc4", 'producte4',"2023-02-15","2023-02-15","calaix4","2023-02-15","2023-02-15"),
("estoc5", 'producte5',"2023-02-15","2023-02-15","calaix5","2023-02-15","2023-02-15");