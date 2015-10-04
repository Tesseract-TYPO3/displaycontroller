.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-realurl:

RealURL
^^^^^^^

The Display Controller provides a user object for creating speaking
URLs for any link to a single record in conjunction with RealURL.

.. important::

   This mechanism is not really supported anymore as it proved pretty
   touchy to use and too dependent on changes in RealURL.
   It will be dropped in a future version.

A typical configuration will look like:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'] = array(
           'item' => array(
                   array(
                           'GETvar' => 'tx_displaycontroller[table]',
                           'valueMap' => array(
                                   'news' => 'tt_news'
                           ),
                   ),
                   array(
                           'GETvar' => 'tx_displaycontroller[showUid]',
                           'userFunc' => 'Tesseract\\Displaycontroller\\Utility\\RealUrlTranslator->main',
                           'userFunc.' => array(
                                   'tt_news ' => array(
                                           'alias_field' => 'title',
                                   ),
                           )
                   ),
           )
   );

Note how the postVarSets configuration uses the "item" key.
This is the default key expected by the Display Controller's
RealURL user-function. It's possible to use another key, but it must
be defined in the TypoScript configuration:

.. code-block:: typoscript

   plugin.tx_displaycontroller.detailView.postVarSets = item

When creating a link to a single record, the Tesseract requires the
link to be built using :code:`tx_displaycontroller[table]` and
:code:`tx_displaycontroller[showUid]` query parameters, the first one
containing the table's name and the second the primary key of the
record. The Display Controller provides RealURL with a user object to
transforms these parameters into a speaking URL.

In the above configuration the first parameter
(:code:`tx_displaycontroller[table]`) is mapped to a list of tables.
The key used in the array will be the name of the table as a segment
in the speaking URL. The second parameter
(:code:`tx_displaycontroller[showUid]`) refers to a user function for
generating the alias with the proper field (of the proper table, since
it will refer to :code:`tx_displaycontroller[table]`.

It is very important to keep these two configurations (for "table" and
for "showUid") exactly in that order because it is expected to be this
way. Setting a different order or using other indices in the
configuration array will break the encoding/decoding process.

If the "alias\_field" property is missing, the Display Controller will
use the "uid" field as a fall-back.

The "alias\_field" property in the configuration can be more than a
simple string. It can contain expressions that will be interpreted by
the expressions parser. Example:

.. code-block:: php

   'alias_field' => 'header_{date:Y}'

Another feature is to use a marker called :code:`###LANG###` which contains
the 2-letter ISO code associated with the language in which the URL is
being generated. The code is deducted from the language value map
entered in the RealURL configuration. Given the following
configuration:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['preVars'] = array(
           array(
                   'GETvar' => 'L',
                   'valueMap' => array(
                           'fr' => '0',
                           'en' => '1',
                           'de' => '2',
                           'it' => '3'
                   ),
                   'valueDefault' => 'fr',
                   'noMatch' => 'bypass',
           ),
   );

the :code:`###LANG###` marker will contain "fr" when language is 0 or
undefined (default), "en" when language is 1, etc. The "alias\_field"
configuration would then look like:

.. code-block:: php

   'alias_field' => 'header_###LANG###'

Note that to make the language configuration more easily accessible
for the Display Controller, it is possible to use the "lang" key as
index to the language configuration. So the above configuration would
be written as:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['preVars'] = array(
           'lang' => array(
                   'GETvar' => 'L',
                   'valueMap' => array(
                           'fr' => '0',
                           'en' => '1',
                           'de' => '2',
                           'it' => '3'
                   ),
                   'valueDefault' => 'fr',
                   'noMatch' => 'bypass',
           ),
   );


.. _configuration-realurl-troubleshooting:

Troubleshooting
"""""""""""""""

If anything seems wrong with the speaking URLs, don't forget to check
both ends of the process, i.e.:

- if the link is assembled with properly named variables (i.e.
  :code:`tx\_displaycontroller[table]` and
  (:code:`tx\_displaycontroller[showUid]`)

- if the RealURL configuration is correct (i.e. all tables are listed
  and an alias field is defined for each)

Activating the "debug" flag in the extension's configuration will log
information to the Developer's Log, which may help you track errors in
the generation of speaking URLs.

