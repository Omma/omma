# Open Meeting Manager

## Struktur

- app:
  - config: Konfiguration der Anwendung und verwendeten Bundles
  - Resources: Globale Templates
  - serializer: Überschreibung von Einstellungen der Serialisierung von Entities
    aus Third Party Bundles
- bin: Ausführbare Dateien von Third Party Bundles
- node_modules: Node.js Abhängigkeiten
- src: PHP Anwendungscode
  - Application: Erweiterung/Überschreibung von Third Party Bundles
  - Omma: PHP Anwendungscode
- vendor: PHP Abhängigkeiten
- vm: Vagrant-VM Konfiguration
- web: Auf dem Webserver öffentlich zugängliche Dateien (DocumentRoot)
  - assets: Javascript- & CSS-Dateien
    - build: Mit Grunt generierte Dateien
    - components: Über Bower installierte Abhängigkeiten
    - fonts: Schriftarten für den Browser (Icon-Fonts)
    - img: Bilder
    - js: Frontend Javascript-Code
    - less: Less-Dateien, aus denen CSS generiert wird
    - templates: AngularJS Templates


## Installation

Composer installieren:

```
curl -sS https://getcomposer.org/installer | php
```

Composer Abhängigkeiten installieren:

```
composer install
```

JavaScript Abhänigkeiten installieren:

```
npm install
```

Bower und Grunt installieren:

```
npm install -g bower
npm install -g grunt-cli

```

Bower Abhängigkeiten installieren

```
bower install
```

Javascript und CSS Dateien mit Grunt generieren:

```
grunt
```

## Test Benutzer erstellen

```
# php app/console fos:user:create
Please choose a username:admin
Please choose an email:admin
Please choose a password:admin
```

Zum Super-Admin machen

```
# php app/console fos:user:promote admin
Please choose a role:ROLE_SUPER_ADMIN
```  

## Vagrant

Mit [Vagrant](https://www.vagrantup.com/) können virtuelle Maschinen für die Entwicklung einfach und wiederholbar aufgesetzt werden.

Standardmäßig wird dazu Virtualbox verwendet, kann aber auch mit VMWare (kostenpflichtig) oder Parallels Desktop benutzt werden.

### Vorbereitung

`config.rb.example` in `config.rb` kopieren und editieren.
Wichtige Einstellung ist der `shared_folder_type`. NFS ist nur auf Linux und Mac OS X verfügbar und muss in `smb` oder `virtualbox` (Virtualbox shared folder) geändert werden. Alternativ kann wohl NFS mit Winnfsd unter Windows mit einem Vagrant Plugin (https://github.com/GM-Alex/vagrant-winnfsd) verwendet werden.

### VM starten
```
vagrant up
```

Beim ersten Start kann eine Fehlermeldung im zusammenhang mit MySQL kommen. In dem Fall muss ein

```
vagrant provision
```

ausgeführt werden, dass dann ohne Fehlermeldung beendet werden sollte.

### Auf VM zugreifen
Über SSH kann auf die VM zugegriffen werden:

```
vagrant ssh
```
