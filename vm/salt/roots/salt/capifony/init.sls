ruby:
  pkg.installed: []

capifony:
  gem.installed:
    - require:
      - pkg: ruby
