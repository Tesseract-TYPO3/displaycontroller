.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-tsconfig:

Page TSconfig
^^^^^^^^^^^^^

The Display Controller comes with a Page TSconfig option that makes it
possible to act on the "Save and view" button in the BE and call up a
properly configured preview page for any record (assuming that page
contains appropriately configured Tesseract components, of course).

The basic syntax is:

.. code-block:: typoscript

   tx_displaycontroller.tx_foo_bar {
           previewPid = 1498
           parameters = &item=###id###&L=###lang###
   }

where :code:`tx_foo_bar` is the name of the table.

.. note::

   This mechanism will be dropped in a future version,
   since TYPO3 CMS 7 ships with such a feature out of the box.


.. _configuration-tsconfig-properties:

Properties
""""""""""


.. _configuration-tsconfig-properties-previewpid:

previewPid
~~~~~~~~~~

Data type
  integer

Description
  The id of the page that should be called up for preview.


.. _configuration-tsconfig-properties-parameters:

parameters
~~~~~~~~~~

Data type
  string

Description
  The list of query string variables that should be added to the call to
  the preview page, so that it receives all the information that it
  needs to display the record being "saved and viewed".

  Three markers can be used in this string:

  - :code:`###id###` is replaced by the id of the record that has just
    been saved

  - :code:`###table###` is replaced by the name of the table being handled

  - :code:`###lang###` is replaced by the id of the current language (0
    for the default language)

  The Display Controller automatically adds :code:`&no_cache=1`.

Default
  &tx\_displaycontroller[table]=###table###&tx\_displaycontroller[showUid]=###id###&L=###lang###

Whatever syntax you put in the parameters should obviously match the
Data Filter used on the preview page, and whatever Data Consumer
exists there should also be able to display a record from the given
table.


.. _configuration-tsconfig-example:

Example
"""""""

Given the following Page TSconfig:

.. code-block:: typoscript

    tx_displaycontroller.tx_news_domain_model_news {
          previewPid = 36
          parameters = &tx_displaycontroller[news]=###id###&L=###lang###
    }

when the "Save and view" button is hit, the following URL is called up:

.. code-block:: text

    http://www.foo.com/index.php?id=36&tx_displaycontroller[news]=11&L=0&no_cache=1


.. _configuration-tsconfig-warning:

Warning
"""""""

This preview mechanism may conflict with other such mechanisms. This
happens – for example – with extension "linkhandler". The problem with
"linkhandler" is that the same config (mod.tx\_linkhandler) is used
for both creating the extra tabs and previewing. So you can't disable
one while keeping the other. If you encounter this problem, the
simplest solution is probably to deactivate the hook registered by
"linkhandler". Edit file :file:`ext_localconf.php` and comment out the
following lines:

.. code-block:: php

    include_once t3lib_extMgm::extPath($_EXTKEY) . 'service/class.tx_linkhandler_tcemain.php';
    $GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/service/class.tx_linkhandler_tcemain.php:tx_linkhandler_tcemain';

