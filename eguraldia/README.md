# Eguraldiaren iragarpena

Eguraldiaren iragarpena egiten duen Web aplikazioa garatu behar duzue. Aplikazioak, udalerrien iragarpena egingo du. Erabiltzaileak udalerria aukeratuko du eta aplikazioak bere iragarpena erakutsiko du:

**Udalerria aukeratu:**

![udalerriak](udalerriak.png)

**Iragarpena:**

![eguraldi-iragarpena](eguraldi-iragarpena.png)

Beharrezko dituzun datuak (irudiak, tenperatura, prezipitazioa, haizea, ...) [euskalmet](https://www.euskalmet.euskadi.eus/hasiera/) edo [aemet](https://www.aemet.es/eu/portada) web guneetatik hartu ditzakezue. Udalerrien zerrenda https://www.euskalmet.euskadi.eus/media/assets/resources/webmet00-locations.json

- Uneko iragarpena (orain)
- Gaurko, biharko eta etziko iragarpena orduka
- 6 eguneko joera

DB proposamena (*zuek aldatu edo egokitu dezakezue):

```ascii
+--------+           +------------------+           +-------------------+
| herria |---<1:N>---| iragarpena-eguna |---<1:N>---| iragarpena-orduko |
+--------+           +------------------+           +-------------------+

herria:
- izena
iragarpena:
- eguna
- iragarpena-testua
- eguraldia (oskarbi, hodei-gutxi, ...)
- tenperatura-minimoa
- tenperatura-maximoa
iragarpena-orduko:
- ordua (00:00, 01:00, ...)
- eguraldia (oskarbi, hodei-gutxi, ...)
- tenperatura
- prezipitazioa
- haizea-nondik
- haizea-km/h
```
## MVC

```ascii
             [user]
                |
                |<------------------ +
                |                    |
                v                    |
         (Public/Router)             |
                |                    |
                |                    |
                v                    |
(model)<===>(Controller)---->(view)->+
```

```ascii
/MVC_eguraldia
├── models/
│   ├── Herria.php
│   ├── IragarpenEguna.php
│   └── IragarpenaOrduko.php
├── views/
│   ├── Herri-zerrenda.php
│   └── ...
├── controllers/
│   ├── HerriaController.php
│   ├── IragarpenEgunaController.php
│   └── IragarpenaOrdukoController.php
├── public/
│   ├── index.php
│   ├── herriak.php
│   └── ...
└── config/
    └── config.php
```
