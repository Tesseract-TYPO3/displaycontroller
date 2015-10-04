.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-typoscript:

TypoScript
^^^^^^^^^^

This chapter describes the TypoScript configuration that applies to "displaycontroller".


.. _configuration-typoscript-properties:

Properties
""""""""""


.. _configuration-typoscript-properties-redirect:

redirect
''''''''

Data type
  :ref:`Redirection configuration <configuration-typoscript-redirection>`

Description
  Use this configuration to redirect the process to some other page,
  based on a condition.


.. _configuration-typoscript-properties-listview:

listView
''''''''

Data type
  :ref:`List view configuration <configuration-typoscript-list-view>`

Description
  Configuration for the list view. This is generally default values that
  may be superseded by GET/POST parameters.

  .. note::

     Usage of the default list view is deprecated and will be dropped
     in a future version.


.. _configuration-typoscript-properties-detailview:

detailView
''''''''''

Data type
  :ref:`Detail View Configuration <configuration-typoscript-detail-view>`

Description
  Configuration for the detail. Currently this is just about setting a
  different keyword for the RealURL postVarSets (see "RealURL" below).

  .. note::

     Usage of the default detail view is deprecated and will be dropped
     in a future version.


.. _configuration-typoscript-redirection:

Redirection configuration
"""""""""""""""""""""""""

.. _configuration-typoscript-redirection-enable:

enable
''''''

Data type
  boolean / :ref:`stdWrap <t3tsref:stdwrap>`

Description
  Enable the redirection mechanism at all (redirection still depends on
  the condition below)


.. _configuration-typoscript-redirection-condition:

condition
'''''''''

Data type
  :ref:`if <t3tsref:if>`

Description
  Condition for the redirection to take place. Note that you have the
  following data available:

  - the total count of records in the SDS in a register called
    "sds.totalCount"

  - the count of records in the SDS in a register called "sds.count"

  - the data in the first record of the SDS, loaded in the content object
    (and thus available with the "field" syntax in the getText function)


.. _configuration-typoscript-redirection-url:

url
'''

Data type
  :ref:`typolink <t3tsref:typolink>`

Description
  URL to redirect to. Note that data from the first record of the SDS is
  available here too, as described above.


.. _configuration-typoscript-list-view:

List view configuration
"""""""""""""""""""""""


.. _configuration-typoscript-list-view-limit:

limit
'''''

Data type
  int

Description
  Maximum number of records to display

Default
  10


.. _configuration-typoscript-list-view-sort:

sort
''''

Data type
  string

Description
  Field name for default sorting of results. Syntax is similar to SQL,
  i.e. :code:`tablename.fieldname` (table name can be omitted)


.. _configuration-typoscript-list-view-order:

order
'''''

Data type
  string

Description
  Default ordering of records. Acceptable values are **asc** or
  **desc**.


.. _configuration-typoscript-detail-view:

Detail View Configuration
"""""""""""""""""""""""""

.. _configuration-typoscript-detail-view-postvarsets:

postVarSets
'''''''''''

Data type
  string

Description
  Name of the key that defines the postVarSets configuration for
  RealURL.

Default
  item
