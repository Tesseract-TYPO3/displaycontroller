.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-query-parameters:

Query parameters
^^^^^^^^^^^^^^^^

Since the Display Controller exists in two versions (pi1 and pi2), it
uses a common naming for its GET/POST variables, i.e.
:code:`tx_displaycontroller[xxx]` , so that both plug-ins use the
same syntax. Any variable named according to that scheme will be
available in the piVars of the Display Controller. This also means
that they will be available as "vars:" in the "datafilter" extension,
since it relies on "expressions".

.. note::

   Usage of the variables below is deprecated. It will be removed
   in a future version of "displaycontroller".

Furthermore the Display Controller recognizes a number default
GET/POST variable names with which it builds the "Detail view" and
"List view" described above:

- :code:`tx\_displaycontroller[table]` : table used for the detail view

- :code:`tx\_displaycontroller[showUid]` : primary key of the record to
  display in the detail view. Used in conjunction with the "table"
  parameter above, this uniquely defines a given record

- :code:`tx\_displaycontroller[max]` : for the list view with page
  browsing, how many records to display at a time

- :code:`tx\_displaycontroller[page]` : for the list view with page
  browsing, number of the current page (expected to start at 0)

- :code:`tx\_displaycontroller[sort]` : for the list view, field on
  which to sort the records (may use the syntax "table.field")

- :code:`tx\_displaycontroller[order]` : order for sorting ("asc" or
  "desc", defaults to "asc").

Extension "templatedisplay" has an object type that builds links to
detail views using the variables names described above. It also uses
the proper variables names when creating a page browser. Note that
these variables' names are not hard-coded, but are provided by the
controllers themselves via an API.
