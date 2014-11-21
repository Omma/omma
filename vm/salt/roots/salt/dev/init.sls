python-pip:
  pkg.installed: []

httpie:
  pip.installed:
    - require:
        - pkg: python-pip
