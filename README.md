Open Meeting Manager
====================

Installation
------------

Composer installieren:

```
curl -sS https://getcomposer.org/installer | php
```

Composer Abhängigkeiten installieren:

```
composer install
```

Bower installieren:

```
npm install -g bower
```

Bower Abhängigkeiten installieren

```
bower install
```

Grunt installieren:

```
npm install -g grunt-cli
```

Grunt aussführen:

```
grunt
```

Test Benutzer erstellen
-----------------------

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
