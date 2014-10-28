# add ppa key to avoid unicode errors (https://github.com/saltstack/salt/issues/8421)
ondrej-ppa-key:
  cmd.run:
    - name: "apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C > /dev/null 2&>1 && apt-get -q update"

php_packages:
  pkgrepo.managed:
    - ppa: ondrej/php5-5.6
    - require:
      - pkg: python-software-properties
      - cmd: ondrej-ppa-key
  pkg.installed:
    - pkgs:
      - php5-cli
      - php5-curl
      - php5-gd
      - php5-intl
      - php5-mysql
      - php5-xdebug
      - php5-xsl
      - php5-gearman
      - php5-mcrypt
      - php5-ldap

php5-fpm:
  pkg:
    - installed
    - require:
      - pkg: php_packages
  service:
    - running
  watch:
    - pkg: php5-fpm

build-essential:
  pkg.installed: []

/etc/php5/fpm/pool.d/www.conf:
  file.managed:
    - source: salt://php/templates/www.conf.j2
    - template: jinja
    - watch_in:
      - service: php5-fpm

{% for config in ['time', 'dev'] %}
{{ config }}-config:
  file.managed:
    - name: /etc/php5/mods-available/{{ config }}.ini
    - source: salt://php/templates/{{ config }}.ini
    - watch_in:
      - service: php5-fpm

{% for module in ['fpm', 'cli'] %}
{{ config }}-config-{{ module }}:
    file.symlink:
    - name: /etc/php5/{{ module }}/conf.d/30-{{ config }}.ini
    - target: /etc/php5/mods-available/{{ config }}.ini
    - watch:
      - file: {{ config }}-config
    - watch_in:
      - service: php5-fpm
{% endfor %}


{% endfor %}
