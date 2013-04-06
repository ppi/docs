PPI Documentation
=================

The [PPI Framework](http://www.ppi.io/) documentation.

[![Build Status](https://travis-ci.org/ppi/docs.png?branch=2.1)](https://travis-ci.org/ppi/docs)

Documentation Format
--------------------

The PPI2 documentation uses [reStructuredText](http://docutils.sourceforge.net/rst.html) as its markup language and
[Sphinx](http://sphinx-doc.org/) for building the output (HTML, PDF, ...).

Please refer to both project homepages to learn it's syntax if you're not used with reST/Sphinx yet.

Generating the Documentation
----------------------------

To build the documentation you'll need Python and Sphinx installed.

```bash
$ apt-get install python2.7
$ easy_install sphinx
$ cd /path/to/ppi-docs
$ make html
```

Contributing
------------

The PPI2 documentation is hosted on GitHub:

    https://github.com/ppi/docs

To submit a contribution, fork the official repository on GitHub and send a [Pull Request](https://help.github.com/articles/using-pull-requests).

Like PPI Framework source code, the documentation repository is split into three branches: 2.0 for the current PPI 2.0.x release, 2.1 for the current PPI 2.1.x release and master as the development branch for upcoming releases.

Translations
------------

Contributing translations requires that you make a new directory using the two letter name for your language. As content is translated, directories mirroring the english content should be created with localized content.
