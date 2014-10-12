ssh:
  service.running: []

/etc/ssh/sshd_config:
  file.managed:
    - source: salt://common/templates/sshd
    - template: jinja
    - watch_in:
      - service: ssh

{% for user, details in pillar['users'].items() %}

{{ details.home }}/.ssh/config:
  file.managed:
    - source: salt://common/templates/ssh_config
    - template: jinja
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{{ user }}-ssh-key:
  cmd.run:
    - name: ssh-keygen -N "" -b 4096 -f {{ details.home }}/.ssh/id_rsa -C {{ user }}@{{ grains['fqdn'] }}
    - unless: stat {{ details.home }}/.ssh/id_rsa.pub
    - user: {{ user }}
    - require:
      - user: {{ user }}

{% endfor %}
