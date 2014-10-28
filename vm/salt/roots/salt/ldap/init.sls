# installing ldap

slapd:
  pkg.installed:
    - require:
      - debconf: ldap-conf

ldap-utils:
  pkg.installed: []


ldap-conf:
  debconf.set:
    - data:
        'slapd/password1': {'type': 'password', 'value': 'install' }
        'slapd/password2': {'type': 'password', 'value': 'install' }
        'shared/organization': {'type': 'string', 'value': 'omma' }
        'slapd/no_configuration': {'type': 'boolean', 'value': False }
        'slapd/move_old_database': {'type': 'boolean', 'value': True }
        'slapd/domain': {'type': 'string', 'value': 'omma.local' }
        'slapd/backend': {'type': 'select', 'value': 'HDB' }
        'slapd/allow_ldap_v2': {'type': 'boolean', 'value': False }
    - require:
      - pkg: debconf-utils

/tmp/omma.ldif:
  file.managed:
    - source: salt://ldap/templates/omma.ldif

ldap-install:
  cmd.run:
    - name: 'service slapd stop && slapadd -n 1 -l /tmp/omma.ldif && service slapd start'
    - unless: 'ldapsearch -x -b uid=test,ou=users,dc=omma,dc=local'
    - require:
      - pkg: slapd
      - pkg: ldap-utils
      - file: /tmp/omma.ldif

phpldapadmin-git:
  git.latest:
    - name: https://github.com/leenooks/phpLDAPadmin.git
    - target: /home/vagrant/web/phpldapadmin
    - user: vagrant
    - group: vagrant

/home/vagrant/web/phpldapadmin/config/config.php:
  file.managed:
    - source: salt://ldap/templates/config.php
    - user: vagrant
    - group: vagrant
    - require:
      - git: phpldapadmin-git

/etc/nginx/sites-available/phpldapadmin.conf:
  file.managed:
    - source: salt://ldap/templates/nginx.conf
    - template: jinja
    - require:
      - pkg: nginx
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/phpldapadmin.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/phpldapadmin.conf
    - watch:
      - file: /etc/nginx/sites-available/phpldapadmin.conf
    - watch_in:
      - service: nginx
