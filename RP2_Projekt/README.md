# RP2_Projekt

<mark>Dokumentacija za bazu: https://www.overleaf.com/6726364111xqzgvmphxsbf</mark>
## Kodovi za narudžbe: (novo! 6.7.)
U bazi stupac active može poprimati:
* -2 -> odbijeno od dostavljača (ni jedan nije prihavtio narudžbu ili nema slobodnih)
* -1 -> odbijeno od restorana
* 0 -> narudžba je dostavljena
* 1 -> user je poslao narudžbu restoranu
* 2 -> restoran je prihvatio narudžbu
* 3 -> dostavljač je prihvatio narudžbu


### [NOVO] Još za napraviti
* +user - u košaricu kod popisa hrane dodati sliku te hrane
* +user - gumb narudžba - ispiše se forma koja traži unos adrese narudžbe, kvarta i napomene za dostavljača tek nakon unosa potrebnog se podaci o narudžbi pošalju bazi
* +user - ispis prema lokaciji - korisnik odabere kvart....napravljeno (dodana nova tablica u bazi spiza_neighborhood, dodano u dokumentaciji svugdje osim u grafu)
* restoran - prevesti sve na hrvatski, provjeriti gumbe 
* dostavljači - dodati listu slobodniih narudžbi - omogućiti da prihvati narudžbu i doda vrijeme ili da odbije 


### Promjene
* dodano u bazu in_offering u spiza food - flag 0 ili 1, 1 ako je u ponudi jelo 0 ako je izbrisano iz ponude
* slike se nalaze u folderu /app/images/food/ (`id_food` -> id_food.jpg/jpeg )
* `javascript`file-ovi se nalaze u /view/javascript
* `php` skripte se nalaze u /app/
* Trenutno radim na indexu restorana i dodajem slike restorana - Fran
* Napravljeno da useru po log inu pokazuje popis njemu najdražih restorana (onih kojima je on davao najbolju ocjenu)
* Doda popis korisnikovih narudžbi
* Doda košaricu u prikazu restorana
* u bazi spiza_food_type dodan image_path
* radim na stranici svi restorani i svim popratnim stranicama koje ponudimo u menuu na "svi rastorani" - Manuela
* napravljen popis po tipu hrane preko sličice 
* dovršen popis najpopularnijih restorana (trenutno ispisuje sve restorane prema ocjenama drugih korisnika)
* Dodana mogućnost mijenjanja detalja o restoranu
* Namistija ispis narudžbi za novi column quantity i doda ocjenjivanje u moje narudžbe
* Doda thumbs up i down za recenzije

#### Za inicijaliziranje baze:
* Treba u app/database/db.class namjestit podatke za login u bazu
* Za kreiranje tablica potrebno je pokrenuti na serveru file-ove 

###### Napomena:
* U css folderu treba ostaviti file style_404.css
* Registracija korisnika radi samo ako se pokreće na rp2 serveru


#### Baza podataka
* baza users je ista kao u dz2
* restaurants baza ima restorane Pizzeria 6, Pizzeria Bros, Rocket Buger, Submarine, Batak Grill, Kokopeli, R&B Food House Of Ribs, Kineski restoran Peking, Koykan World Food - Tkalčićeva, Pizzeria Zagabria
* liste restorana po vrsti hrane: pizza, burger, grill, meksicka, kineska
    !!neki restorani mogu spadati u više vrsta hrane!!
* baza orders ima već nekoliko narudžbi pa da se pazi da već neke narudžbe postoje

### Opis:
#### Početna stranica:
* Odaberite jeste li korisnik, restoran ili dostavljač ( tri kvadrata s ikonama korisnika, restorana i dostavljača koje vode na sljedeće stranice )
* Ako ste odabrali KORISNIK, otvara se stranica LOGIN ZA KORISNIKE, gdje se možete ulogirati ako već imate račun ( username i password ), 
    ili registrirati ( username, pass, check pass, email, broj mobitela, adresa, kvart )
* Ako ste odabrali RESTORAN, otvara se LOGIN ZA RESTORANE - LOGIN ( username, pass ), REGISTER ( ime restorana, opis restorana, tip restorana (hrane), 
    username, pass, check pass, email, kontakt broj, adresa, kvart )
* Ako ste DOSTAVLJAČ, LOGIN ZA DOSTAVLJAČE - LOGIN ( username, pass ), REGISTER ( username, pass, check pass, email, kontakt broj )

#### User                                                                                     
* Na svakoj stranici se ispisuje menu: Moji omiljeni restorani, Moje narudžbe, Svi restorani, Logout. (Ovdje dodajme searchbar za pretraživanje ključnih riječi?)
* Nakon ulogiravanja se ispisuje:
    * mogućnost pregleda restorana koji dostavljaju na moju adresu (prema udaljenosti, npr. 10 km)
    * lista Vaši omiljeni restorani: trenutno ispisuje restorane koje je korisnik ocijenio sortirano silazno prema ocjeni, 
    želimo ubaciti još da se gleda broj narudžbi korisnika iz svakog tog restorana, npr.
    Vaši omiljeni restorani:
        Rocket Burger     - ocjena: 9, br_narudzbi: 6
        Pizzeria Zagabria - ocjena: 9, br_narudzbi: 4
        Submarine         - ocjena: 9, br_narudzbi: 3
        Kokopeli          - ocjena: 8, br_narudzbi: 5
* Svi restorani: Prvo prikazuje ispis svih restorana onako kako su upisani u bazu, a onda korisnik može odabrati po kojem kriteriju pretražiti restorane:
	* najpopularniji (po preporukama ostalih korisnika)
	* +svi restorani 
	* najbliži restorani
	* +po tipu hrane (nakon klika na ovo korisnik bira tip hrane preko pripadnih sličica)
* Moje narudžbe: Popis narudžbi sortiran silazno po datumu s time da svaka narudžba ima link na restoran (ispis narudžbe se sastoji od restorana odakle je naručena, popis naručenih jela i datuma narudžbe). Imamo i odvojeni popis aktivnih narudžbi, onih koje još nisu dostavljene.


#### Napomena
Mislim da se funkcije `getRestaurantListByMyRating` i `getMyFeedbackList` ne koriste nigdje, pa ili ih treba uključiti negdje ili obrisati, a nisam ih ja radila pa ne znam što je s time. S time da se u njima koristi neka od starih verzija baze gdje je rating u spiza_restaurants
