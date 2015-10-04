.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-display-cache:

Cached or not cached?
^^^^^^^^^^^^^^^^^^^^^

The Display Controller fronted plug-in comes in two versions: one
cached and one not cached. How to choose which one to use?

The general rule is that the cached version should be preferred
whenever possible for obvious performance reasons. However in some
circumstances using the non-cached version cannot be avoided. A
typical use of the non-cached version is when performing searches: if
there are a lot of possible search criteria, it is not desirable or
even possible to store every combination of search values into cache.
