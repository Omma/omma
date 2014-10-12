users:
  root:
    home: /root
  vagrant:
    home: /home/vagrant
#{% for user, details in salt['grains.get']('users', {}).items() %}
#  {{ user }}:
#    name: {{ user }}
#    home: {{ details.get('home', '/home/' ~ user) }}
#{% endfor %}
