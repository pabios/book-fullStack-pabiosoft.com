> Créez une procédure permettant d'obtenir rapidement la valeur de l'auto-increment pour une table donnée d'une base :

```sql
DELIMITER ;;
CREATE PROCEDURE get_auto_increment(IN tab CHAR(6))
    BEGIN
        SELECT `AUTO_INCREMENT`
		FROM INFORMATION_SCHEMA.TABLES
		WHERE     TABLE_NAME   = tab;
    END;;
DELIMITER ;
```
> test 
```sql
CALL get_auto_increment('trips');
```


> Créez la table planes avec les colonnes suivantes :
```sql
CREATE table planes(
    id int primary key not null auto_increment,
     name VARCHAR(5),
     description TEXT,
     num_flying DECIMAL(8,1)
     );
```

> Vous insererez les données suivantes dans la table planes :
```sql
insert into planes(name,description,num_flying) VALUES 
('A380','Gros porteur',12000.0),
('A340','Avion de ligne quadrireacteur',17000.0),
('A340','Moyen courrier',50000.0);
```
> Créez maintenant la clé étrangère pilots.plane_id (cette clé est exactement du même type que la clé primaire de la table planes)

```sql
AlTER TABLE pilots ADD plane_id int ;

ALTER TABLE pilots ADD FOREIGN KEY( plane_id) REFERENCES planes(id);
```
> Nous voulons créer une table trips 

```sql
CREATE TABLE trips (
    id int primary key not null auto_increment,
     name VARCHAR(255),
     departure VARCHAR(255),
     arrival VARCHAR(255),
     created datetime
     );
```

> N:N
relation entre pilot et trip
Contrainte: 
* Un pilote (pilots) peut effectuer un ou plusieur trajet 
* Et un vol (trips) peut contenir un ou plusieur pilots 
(voir shema image)
```sql
CREATE TABLE pilot_trip(
 pilot_id VARCHAR(6) not null, trip_id int not null, 
CONSTRAINT pilot_trip_pilot foreign key (pilot_id) references pilots(certificate), CONSTRAINT pilot_trip_trip foreign key (trip_id) references trips(id), CONSTRAINT pilot_trip_unique UNIQUE (pilot_id,trip_id) );
```

> on renome le champs pilot_id to certificate
```sql
ALTER TABLE pilot_trip RENAME COLUMN pilot_id TO certificate ;
```
> insertion de donner
```sql
INSERT INTO `trips`
  (`name`, `departure`, `arrival`, `created`)
VALUES
  ('direct', 'Paris', 'Brest',  '2020-01-01 00:00:00'),
  ('direct', 'Paris', 'Berlin',  '2020-02-01 00:00:00'),
  ('direct', 'Paris', 'Barcelone',  '2020-08-01 00:00:00'),
  ('direct', 'Amsterdan', 'Brest',  '2020-11-11 00:00:00'),
  ('direct', 'Alger', 'Paris',  '2020-09-01 00:00:00'),
  ('direct', 'Brest', 'Paris',  '2020-12-01 00:00:00');

INSERT INTO `pilot_trip`
  (`certificate`, `trip_id`)
VALUES
  ('ct-10', 1),
  ('ct-6', 2),
  ('ct-100', 1),
  ('ct-11', 3),
  ('ct-12', 4),
  ('ct-10', 4),
  ('ct-12', 5);
```

> 4 - Quels sont les pilotes qui n'ont pas de trajet ?

```sql
select p.certificate,p.name 
from pilots as p
left join pilot_trip as pt on p.certificate = pt.certificate 
where pt.certificate IS NULL;
```
> 5- Sélectionnez les trajets des pilotes avec leurs noms et certifications.
```sql
SELECT p.name,p.certificate,t.name as typeTrajet ,pt.trip_id     
FROM pilots as p    
left join pilot_trip as pt ON p.certificate = pt.certificate    
inner join trips as t on t.id = pt.trip_id;
 ```

 > 6 - Sélectionnez les départs des pilotes par certification.

 ```sql
SELECT p.name,p.certificate,t.departure as depart 
FROM pilots as p      
left join pilot_trip as pt ON p.certificate = pt.certificate     
inner join trips as t on t.id = pt.trip_id;
```

> 7 -Sélectionnez les trajets de tous les pilotes.

```sql 
SELECT p.name,p.certificate,t.name as typeTrajet ,t.departure as depart, t.arrival as arriver ,t.created as heure      
FROM pilots as p      
left join pilot_trip as pt ON p.certificate = pt.certificate     
inner join trips as t on t.id = pt.trip_id;
```