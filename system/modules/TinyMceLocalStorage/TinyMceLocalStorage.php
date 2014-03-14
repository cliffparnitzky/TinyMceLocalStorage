<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    TinyMceLocalStorage
 * @license    LGPL
 */

/**
* Class TinyMceLocalStorage
*
* Class to implement the HOOK for adding configs.
* @copyright  Cliff Parnitzky 2014
* @author     Cliff Parnitzky
*/
class TinyMceLocalStorage {
	
	/**
	 * Adding config for output behavoir
	 */
	public function editTinyMcePluginLoaderConfig ($arrTinyConfig) {
		$arrTinyConfig["setup"] = $this->getJavaScript();
		return $arrTinyConfig;
	}
	
	/**
	 * Returns the minified and translated javascript function.
	 * Orginal code by Lingo4you aka Babelfisch (https://community.contao.org/de/showthread.php?48900-Offline-speichern-(Local-Storage)-in-TinyMCE&p=315513&viewfull=1#post315513)
	 */
	private function getJavaScript () {
		$js = "
function(ed)
{
	ed.onSubmit.add(function(ed, e) {
		localStorage.setItem(storeKey, ed.getContent({format:'html'}));
	});
	ed.onInit.add(function(ed) {
		storeKey = document.URL.replace(/&ref=/, '').replace(/&ref=/, '').replace(/&rt=[^&]+&?/, '').replace(/^.*\?/, '').replace(/fee=1/, '').replace(/[=&]/g, '-');
		/* TODO if there is '&pid=...' in the url, remove the '&id=...' */
		storedContent = localStorage.getItem(storeKey);
		if (storedContent !== null && storedContent != '') {
			/* TODO get current text and remove [nbsp] */
			if (storedContent != ed.getContent({format:'html'})) {
				setTimeout(function() {
					if (confirm('" . $GLOBALS['TL_LANG']['MSC']['TinyMceLocalStorage']['RestoreConfirm'] . "')) {
						ed.setContent(storedContent);
					}
					localStorage.removeItem(storeKey);
				}, 1000);
			}
		}
	});
}
";
		$js = str_replace(array("\r", "\n", "\t"), "", $js);
		return $js;
	}
}
 
?>