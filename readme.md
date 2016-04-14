redCOMPONENT Template
==================================================

How to build your own Template
----------------------------

Clone a copy of the main Template git repo by running:

```bash
git clone git@github.com:redCOMPONENT-COM/wright.git
```

Copy build.properties.dist to build.properties, and edit to match your configuration

Execute to create install package

```bash
phing -f packager.xml
```

