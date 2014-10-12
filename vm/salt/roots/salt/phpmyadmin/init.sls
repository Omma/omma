/home/vagrant/web/phpmyadmin:
  file.directory:
    - user: vagrant
    - group: vagrant
    - makedirs: true
    - require:
      - user: vagrant

phpmyadmin-download:
  cmd.run:
    - name: 'curl -L http://sourceforge.net/projects/phpmyadmin/files/phpMyAdmin/4.2.3/phpMyAdmin-4.2.3-english.tar.gz/download\#\!md5\!95b299daff8d2728707846664abded4a -o /home/vagrant/phpmyadmin.tar.gz'
    - unless: test -f /home/vagrant/phpmyadmin.tar.gz
    - user: vagrant
    - require:
      - user: vagrant
      - file: /home/vagrant/web/phpmyadmin

phpmyadmin-extract:
  cmd.run:
    - name: 'tar xfvz phpmyadmin.tar.gz -C /home/vagrant/web/phpmyadmin --strip-components=1'
    - unless: test -f /home/vagrant/web/phpmyadmin/config.sample.inc.php
    - user: vagrant
    - require:
      - user: vagrant
      - cmd: phpmyadmin-download

phpmyadmin-config:
  file.managed:
    - name: /home/vagrant/web/phpmyadmin/config.inc.php
    - source: salt://phpmyadmin/templates/config.inc.php
    - template: jinja
    - user: vagrant
    - group: vagrant
    - require:
      - user: vagrant
      - cmd: phpmyadmin-extract

/etc/nginx/sites-available/phpmyadmin.conf:
  file.managed:
    - source: salt://phpmyadmin/templates/nginx.conf
    - template: jinja
    - require:
      - pkg: nginx
    - watch_in:
      - service: nginx

/etc/nginx/sites-enabled/phpmyadmin.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/phpmyadmin.conf
    - watch:
      - file: /etc/nginx/sites-available/phpmyadmin.conf
    - watch_in:
      - service: nginx
