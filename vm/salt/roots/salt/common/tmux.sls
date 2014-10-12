tmux:
  pkg:
    - installed

{% for user, details in pillar['users'].items() %}

{{ details.home }}/.tmux.conf:
  file.managed:
    - source: salt://common/templates/tmux.conf
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{{ user }}-tmux-powerline:
  git.latest:
    - name: https://github.com/Wurzel3/tmux-powerline.git
    - target: {{ details.home }}/.tmux/tmux-powerline
    - user: {{ user }}
    - require:
      - user: {{ user }}

{{ details.home }}/.tmux-powerlinerc:
  file.managed:
    - source: salt://common/templates/tmux-powerlinerc
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{% endfor %}
