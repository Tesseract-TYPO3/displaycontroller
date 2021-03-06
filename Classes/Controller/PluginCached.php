<?php
namespace Tesseract\Displaycontroller\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Plugin 'Display Controller (cached)' for the 'displaycontroller' extension.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class PluginCached extends DisplayController
{
    public $scriptRelPath = 'Classes/Controller/PluginCached.php';    // Path to this script relative to the extension dir.
    public $pi_checkCHash = true;
}