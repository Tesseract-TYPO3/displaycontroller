.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-tsconfig:

Page TSconfig
^^^^^^^^^^^^^

The Display Controller used to come with a Page TSconfig option that made it
possible to act on the "Save and view" button in the BE and call up a
properly configured preview page for any record (assuming that page
contains appropriately configured Tesseract components, of course).
This can be achieved with the TYPO3 CMS Core since version 7 LTS.

A typical syntax would be something like:

.. code-block:: typoscript

    TCEMAIN.preview {
        tx_foo_bar {
            previewPageId = 36
            useDefaultLanguageRecord = 0
            fieldToParameterMap {
                uid = tx_displaycontroller[foo]
            }
        }
    }

where :code:`tx_foo_bar` is the name of the table.

If you had existing configuration, you will have Page TSconfig like:

.. code-block:: typoscript

   tx_displaycontroller.tx_foo_bar {
           previewPid = 1498
           parameters = &tx_displaycontroller[foo]=###id###&L=###lang###
   }

which you should replace with the code above, as it is no longer
supported.
