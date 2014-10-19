nginx:
  pkgrepo.managed:
    - ppa: nginx/stable
    - require:
      - pkg: python-software-properties
  pkg:
    - latest
  service:
    - running
    - watch:
        - pkg: nginx
        - file: /etc/nginx/nginx.conf

/etc/nginx/nginx.conf:
  file.managed:
      - source: salt://nginx/nginx.conf
      - user: root
      - group: root
      - template: jinja

/etc/nginx/sites-enabled/default:
  file.absent:
    - require:
      - pkg: nginx
    - watch_in:
      - service: nginx

/etc/nginx/php.conf:
  file.managed:
    - source: salt://nginx/php.conf
    - template: jinja
    - watch_in:
      - service: nginx

/etc/nginx/fastcgi_params:
  file.managed:
    - source: salt://nginx/fastcgi_params
    - template: jinja
    - watch_in:
      - service: nginx

{% for site in ['status'] %}

/etc/nginx/sites-available/{{ site }}.conf:
  file.managed:
      - source: salt://nginx/sites/{{ site }}.conf
      - template: jinja
      - watch_in:
        - service: nginx

/etc/nginx/sites-enabled/{{ site }}.conf:
  file.symlink:
    - target: /etc/nginx/sites-available/{{ site }}.conf
    - watch:
      - file: /etc/nginx/sites-available/{{ site }}.conf
    - watch_in:
      - service: nginx


{% endfor %}
