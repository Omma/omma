php_packages:
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

php5-fpm:
  pkg:
    - installed
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
