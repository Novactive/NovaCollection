# -*- coding: utf-8 -*-

import os
import sys
sys.path.insert(0, os.path.abspath('.'))
from recommonmark.parser import CommonMarkParser

extensions = ['sphinx.ext.intersphinx',
    'sphinx.ext.todo',
    'sphinx.ext.coverage',
    'sphinx.ext.ifconfig',
    'sphinx.ext.githubpages']

templates_path = ['_templates']
source_parsers = {'.md': CommonMarkParser}
source_suffix = ['.rst', '.md']

master_doc = 'index'

project = u'NovaCollection'
copyright = u'(c) 2017 Novactive'
author = u'Sébastien Morel <s.morel@novactive.com>, Luke Visinoni <l.visinoni@novactive.com>'

version = u'0.1'
release = u'v0.1'

language = None

exclude_patterns = ['_build', 'Thumbs.db', '.DS_Store']

pygments_style = 'sphinx'

todo_include_todos = True

html_theme = 'sphinx_rtd_theme'
html_static_path = ['_static']

htmlhelp_basename = 'NovaCollectiondoc'

latex_documents = [
    (master_doc, 'NovaCollection.tex', u'NovaCollection Documentation',
     u'Sébastien Morel', 'manual'),
]

man_pages = [
    (master_doc, 'novacollection', u'NovaCollection Documentation',
     [author], 1)
]

texinfo_documents = [
    (master_doc, 'NovaCollection', u'NovaCollection Documentation',
     author, 'NovaCollection', 'One line description of project.',
     'Miscellaneous'),
]

intersphinx_mapping = {'https://docs.python.org/': None}
