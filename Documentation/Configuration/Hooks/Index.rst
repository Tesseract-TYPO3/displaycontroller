.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-hooks:

Hooks
^^^^^

There's one hook defined in the Display Controller. It can be used to
manipulate filters after their initialization. At this point a filter
may either be empty or already have some structure read from the
session cache.

The hook must be registered that way:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['displaycontroller']['extendInitFilter'][] = 'VendorName\\MyExtension\\Hooks\\DisplayController';

and a typical implementation might look like:

.. code-block:: php

    namespace VendorName\MyExtension\Hooks;
    class DisplayController {

        /**
         * @param array $filter
         * @param \Tesseract\Displaycontroller\Controller\DisplayController $parentObject
         */
        public function extendInitFilter($filter, $parentObject) {
            // Do some changes to the filter
            return $filter;
        }
    }

The method to implement is called :code:`extendInitFilter` and it
receives 2 arguments. The first one is the array containing the filter
data and the second one is a reference to the Display Controller
itself. The method **must** return the filter, even if no
changes were made to it.

