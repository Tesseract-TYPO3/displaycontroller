.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-redirections:

Redirection mechanism
^^^^^^^^^^^^^^^^^^^^^

The Display Controller comes with a flexible redirection mechanism
whose properties are described earlier :ref:`<configuration-typoscript-properties-redirect>`.
Here is an example configuration:

.. code-block:: typoscript

   plugin.tx_displaycontroller.redirect {
           enable = 1
           condition {
                   value.data = register:sds.totalCount
                   equals = 1
           }
           url {
                   parameter = 15
           }
   }

In this example, the redirection is first enabled. Then the condition
for the redirection to actually take place is set. It will happen when
the total number of records in the SDS is equals to 1. Finally the url
to redirect to is defined as a typolink to page 15.

Don't forget that TypoScript is inherited along the page tree. So a
redirection configuration defined at some point may not be desirable
at some other point down the page tree. Either disable it:

.. code-block:: typoscript

    plugin.tx_displaycontroller.redirect.enable = 0

or cancel it altogether:

.. code-block:: typoscript

    plugin.tx_displaycontroller.redirect >

