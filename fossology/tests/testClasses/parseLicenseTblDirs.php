<?php
/***********************************************************
 Copyright (C) 2008 Hewlett-Packard Development Company, L.P.

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 ***********************************************************/

/**
 * Given a fossology License Broswe page, parse it and return the
 * license table.  The rest of the page can be parsed by the browseMenu
 * class.
 *
 * @param string $page the xhtml page to parse
 *
 * @return assocative array with  Can return an empty array indicating
 * nothing on the page to browse.
 *
 * @todo add in link fixups and adjust consumers
 *
 * @version "$Id:  $"
 * Created on Oct. 17, 2008
 */

require_once ('../commonTestFunc.php');

class parseLicenseTblDirs
{
  public $page;
  private $test;

  function __construct($page)
  {
    if (empty ($page)) { return; }
    $this->page = $page;
  }
  /**
   * function parseLicenseTbl
   * given a fossology license browse page parse the license table on
   * the page.
   *
   * @returns array of empty array if license table on that page.
   */
  function parseLicenseTblDirs()
  {
    $pat ='|.*?id="Lic-.?" align="left"><a href=\'(.*?)\'><b>(.*?)<\/b>|';
    $matches = preg_match_all($pat, $this->page, $tableEntries, PREG_PATTERN_ORDER);
    //print "PLTDIR: tableEntries are:\n"; print_r($tableEntries) . "\n";
    return($this->_createDirList($tableEntries, $matches));
  }

  function _createDirList($toCombine, $matches)
  {
    /*
    * if we have a match, the create return array, else return empty
    * array.
    */
    if ($matches > 0)
    {
      $numTblEntries = count($toCombine[1]);
      $rtnList = array ();
      for ($i = 0; $i <= $numTblEntries-1; $i++)
      {
        $rtnList[$toCombine[2][$i]] = $toCombine[1][$i];
      }
      return ($rtnList);
    }
    else
    {
      return (array ());
    }
  }
}
?>
