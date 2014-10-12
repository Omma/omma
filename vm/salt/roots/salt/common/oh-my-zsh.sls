oh-my-zsh-git:
  git.latest:
    - name: https://github.com/Wurzel3/oh-my-zsh.git
    - target: /usr/src/oh-my-zsh

{% for user, details in pillar['users'].items() %}
{{ details.home }}/.zshrc:
  file.managed:
    - source: salt://common/templates/zshrc
    - template: jinja
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{% endfor %}
