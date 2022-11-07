# Prova UF1
## DAW-MP07-UF1 - Exercici de Desenvolupament web en entorn servidor.

L'objectiu és crear un sistema d'accés que registri les connexions dels nostres usuaris. Un company ja ha implementat els aspectes visuals de la nostra aplicació i només caldrà afegir-hi funcionalitat a la banda del servidor. Disposem dels següents fitxers
([Baixa`ls](https://downgit.github.io/#/home?url=https://github.com/aniollidon/gitbook-php/tree/master/activitats/DAW-MP07/DAW-MP07-UF1/prova-uf1)):
+ `index.php`
+ `hola.php`
+ `style.css`

Com que encara no sabem fer servir una base de dades, anotarem els usuaris en un fitxer JSON com aquest:

```json
{
"aniol@example.com": {"email": "aniol@example.com", "password": "patata123", "name": "Aniol"}
}
```

I les connexions en un fitxer com aquest:

```json
[
{"ip": "192.168.1.8", "user": "aniol@example.com", "time": "2022-10-24 15:27:09", "status": "signup_success"},
{"ip": "192.168.1.8", "user": "aniol@example.com", "time": "2022-10-24 15:27:14", "status": "logoff"},
{"ip": "192.168.1.9", "user": "aniol@example.com", "time": "2022-10-24 15:27:29", "status": "signin_password_error"},
{"ip": "192.168.1.9", "user": "aniol@example.cat", "time": "2022-10-24 15:27:43", "status": "signin_email_error"},
{"ip": "192.168.1.8", "user": "aniol@example.com", "time": "2022-10-24 15:27:51", "status": "signin_success"}
]
```
Per persistir les dades utilitzarem les següents funcions que inclourem al nostre projecte:

> Aquesta no és una manera professional de persistir dades dinàmiques, cal usar base de dades però encara no ho hem treballat

```php
/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}

```

## Es demana:
Abans de res instal·la la següent [applicació](https://www.freescreenrecording.com/thanks-for-download?lang=eu) al teu navegador o fes servir el teu programa per gravar la pantalla preferit. Quan acabis la prova guarda el vídeo a la següent [carpeta del drive](https://drive.google.com/drive/u/1/folders/1XLqrWDZgyDARWY4hrgVb9nr9AjYBbVIv). Recorda que has de realitzar una captura de la pantalla completa!

1. Crea el fitxer `process.php` on s'implementa la funcionalitat de registrar usuaris. **(2 punts)**
    + Assegura't que `process.php` rep les dades mitjançant el mètode `POST`
    + Guarda les dades al fitxer `users.json`
    + Només s'ha de crear un nou usuari si aquest no existeix.
    + De moment no ens preocuparem que aquest fitxer sigui visible.
2. Implementa la funcionalitat de permetre l'accés a usuaris ja registrats al fitxer `users.json`. **(2 punts)**
    + Utilitza el patró PGR i redirigeix a `hola.php` quan l'autenticació sigui correcta, i retorna a `index.php` quan aquesta sigui incorrecte.
    + La creació d'un nou usuari es considerarà una autentificació correcta.
    + Saluda l'usuari fent servir el seu nom a la pàgina `hola.php`
3. Manté l'autentificació activa mitjançant l'ús de sessions durant 1 minut. **(1,5 punts)**
    + L'accés a index.php redirigeix a `hola.php` si fa menys d'un minut des de l'última autentificació.
    + l'accés a `hola.php` redirigeix a `index.php` si l'autentificació no està activa.
4. Permet desautentificar-te mitjanant el botó "tanca la sessió" de la pàgina `hola.php`. **(1 punt)**
    + Aquest formulàri també funcionarà amb el mètode `POST` i ens enviarà a `process.php`.
    + Un cop tancada la sessió ens tornarà a aparèixer la pantalla principal.
5. Guarda al fitxer `connexions.json` els accessos correctes i intents d'accés a l'aplicatiu. **(1,5 punts)**
    + Cal guardar la `IP` de la connexió entrant, el moment d'accés (data, hora, minuts, segons) i l'estat de l'accés (correcte, contrasenya-incorrecte, usuari-incorrecte, nou-usuari, creació-fallida).
    + Mostra els accessos correctes d'un usuari a la pàgina `hola.php`
6. Avisa a l'usuari dels errors en el procés d'autentificació. **(1 punt)**
    + Aquesta informació, cal que s'enviï mitjançant el mètode GET
    + La creació fallida d'un usuari s'ha de mostrar d'una manera diferent de l'accés incorrecte.
    + Cal que el missatge d'error segueixi la línia de disseny de la pàgina.
7. Assegura't que el codi estigui ben documentat, segeixi un mateix format i que compleixi amb els requisits de claredat, la llegibilitat i l'eficiència. **(1 punt)**

## Cal tenir en compte:
+ Al treball entregat no pot aparèixer cap error o warning de PHP.
+ Cal penjar la gravació de la pantalla a la carpeta corresponent del drive. 
+ Recorda penjar l'enllaç del teu github al Classroom.