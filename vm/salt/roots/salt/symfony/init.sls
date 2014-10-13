symfony:
  mysql_user.present:
    - name: symfony
    - host: localhost
    - password: {{ salt['grains.get_or_set_hash']('mysql.symfony', 32, 'abcdefghijklmnopqrstuvwxyz0123456789') }}
    - connection_user: root
    - connection_pass: {{ salt['grains.get']('mysql.root') }}
    - require:
      - pkg: python-mysqldb
      - file: /etc/salt/minion.d/mysql.conf
      - service: mysql
      - mysql_user: root
  mysql_database.present:
    - connection_user: root
    - connection_pass: {{ salt['grains.get']('mysql.root') }}
    - require:
      - pkg: python-mysqldb
      - service: mysql
      - file: /etc/salt/minion.d/mysql.conf
      - mysql_user: root
  mysql_grants.present:
    - grant: all privileges
    - database: symfony.*
    - user: symfony
    - connection_user: root
    - connection_pass: {{ salt['grains.get']('mysql.root') }}
    - require:
      - service: mysql
      - mysql_user: symfony
      - mysql_database: symfony
      - file: /etc/salt/minion.d/mysql.conf


/etc/nginx/sites-available/symfony.conf:
  file.managed:
    - source: salt://symfony/templates/nginx.conf
    - template: jinja
    - require:
      - pkg: nginx
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/symfony.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/symfony.conf
    - watch:
      - file: /etc/nginx/sites-available/symfony.conf
    - watch_in:
      - service: nginx

/vagrant/app/config/parameters.yml:
  file.managed:
    - source: salt://symfony/templates/parameters.yml
    - template: jinja
    - replace: false
