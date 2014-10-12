{% from "common/map.jinja" import common with context %}


vim:
  pkg.installed:
    - pkgs:
      - {{ common.vim }}
      - {{ common.ctags }}

{% for user, details in pillar['users'].items() %}

{{ details.home }}/.vimrc:
  file.managed:
    - source: salt://common/templates/vimrc
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{{ user }}-vim:
  git.latest:
    - name: https://github.com/Marmelatze/vimrc
    - target: {{ details.home }}/.vim_runtime
    - rev: master
    - always_fetch: true
    - user: {{ user }}
    - require:
      - user: {{ user }}

{% endfor %}
