nodejs:
  pkg.installed: []

/usr/bin/node:
  file.symlink:
    - target: /usr/bin/nodejs

npm:
  pkg.installed: []

bower:
  npm.installed:
    - require:
      - pkg: npm

grunt-cli:
  npm.installed:
    - require:
      - pkg: npm
