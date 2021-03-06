Generative Fixtures for Drupal
==============================

[![Build Status](https://travis-ci.org/dantleech/drupal_alice_fixtures.svg?branch=master)](https://travis-ci.org/dantleech/drupal_alice_fixtures)

This is just a thin integration of [Alice](https://github.com/nelmio/alice)
with Drupal (console), check out that documentation to find out more.

Usage
-----

```yaml
# fixtures/articles.yml
node:
    article{1..10}:
        type: article
        title: <sentence()>
        body: <text()>
    article{1..10}:
        type: article
        title: Hey! [ <name()> ]
        body: <text()>
```

```bash
$ ./bin/drupal alice:fixtures-load fixtures
```

To purge fixture content which already exists (based on entity type, will
purge all nodes f.e.):

```bash
$ ./bin/drupal alice:fixtures-load fixtures --purge
```
