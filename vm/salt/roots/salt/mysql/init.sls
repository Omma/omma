mysql-server:
  pkg.installed:
    - require:
      - debconf: mysql-pw-conf
python-mysqldb:
    pkg.installed: []

mysql:
  service:
    - running
    - require:
      - pkg: mysql-server

mysql-pw-conf:
  debconf.set:
    - data:
        'mysql-server/root_password': {'type': 'password', 'value': '' }
        'mysql-server/root_password_again': {'type': 'password', 'value': '' }
    - require:
      - pkg: debconf-utils

/etc/salt/minion.d/mysql.conf:
  file.managed:
    - source: salt://mysql/templates/minion_mysql
    - watch_in:
      - service: salt-minion
    - require:
      - pkg: mysql-server

mysql-root:
  mysql_user.present:
    - name: root
    - host: localhost
    - password: {{ salt['grains.get_or_set_hash']('mysql.root', 32, 'abcdefghijklmnopqrstuvwxyz0123456789') }}
    - require:
      - pkg: python-mysqldb
