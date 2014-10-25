{% from "common/map.jinja" import common with context %}

salt-minion:
  service:
    - running

/etc/salt/grains:
  file.managed:
    - user: root
    - group: root
    - mode: 0600

{% for user, details in pillar['users'].items() %}
{{ user }}:
  user.present:
    - shell: {{ common.zsh_bin }}
    - home: {{ details.get('home', '/home' ~ user)}}
    - uid: {{ details.get('uid', '') }}
    - gid: {{ details.get('gid', '') }}
  group.present:
    - gid: {{ details.get('gid', '') }}

{% endfor %}

{% if grains['os'] == "Debian" %}

dotdeb:
  pkgrepo.managed:
    - humanname: Dotdeb Packages
    - name: deb http://packages.dotdeb.org {{ grains['oscodename']}}{% if grains['lsb_distrib_codename'] == 'squeeze' %}-php54{% endif %} all
    - file: /etc/apt/sources.list.d/packages_dotdeb_org.list
    - key_url: http://www.dotdeb.org/dotdeb.gpg
    - require_in:
      - nginx
      - php5-fpm
      - php5-cli

/etc/apt/apt.conf.d:
  file.recurse:
    - source: salt://common/templates/apt
    - template: jinja
{% endif %}

{% if grains['os_family'] == "Debian" %}
debconf-utils:
  pkg.installed: []

{% endif %}


/usr/bin/archey:
  file.managed:
    - source: salt://common/templates/archey
    - mode: 777


git:
  pkg.installed: []

python-software-properties:
  pkg.installed: []

packages:
  pkg.installed:
    - pkgs:
      - curl
      - zsh
      - lsb-release
      - scrot

/etc/locale.gen:
  file.managed:
    - source: salt://common/templates/locale.gen

locale-gen:
  cmd.wait:
    - name: locale-gen
    - watch:
      - file: /etc/locale.gen

en_US.UTF-8:
  locale.system

include:
  - common.git
  - common.oh-my-zsh
  - common.ssh
  - common.vim
  - common.tmux
