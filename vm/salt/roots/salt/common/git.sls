{% for user, details in pillar['users'].items() %}


{{ user}}-git.w3p.cc:
  ssh_known_hosts:
    - present
    - name: git.w3p.cc
    - port: 7999
    - fingerprint: 12:93:b6:0b:16:f5:10:a1:34:3e:ac:02:61:89:9b:8e
    - user: {{ user }}
    - require:
      - user: {{ user }}

{{ user }}-git-lg:
  cmd.run:
    - name: git config --global alias.lg "log --color --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit"
    - unless: git config --global --get alias.lg
    - user: {{ user }}
    - require:
      - pkg: git
      - user: {{ user }}

{{ user }}-git-ignore:
  cmd.run:
    - name: git config --global core.excludesfile ~/.gitignore_global
    - unless: git config --global --get core.excludesfile
    - user: {{ user }}
    - require:
      - pkg: git
      - user: {{ user }}

{{ details.home }}/.gitignore_global:
  file.managed:
    - source: salt://common/templates/gitignore_global.j2
    - template: jinja
    - user: {{ user }}
    - group: {{ user }}
    - require:
      - user: {{ user }}

{% endfor %}
