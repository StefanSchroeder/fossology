<?php
/***********************************************************
 Copyright (C) 2010 Hewlett-Packard Development Company, L.P.

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
 * Verify special fossology test archive loaded correctly
 *
 * @version "$Id$"
 *
 * Created on March 10, 2010
 */

require_once('../../../tests/fossologyTestCase.php');
require_once('../../../tests/commonTestFuncs.php');
require_once('../../../tests/TestEnvironment.php');
require_once('../../../tests/testClasses/parseBrowseMenu.php');
require_once('../../../tests/testClasses/parseMiniMenu.php');
require_once('../../../tests/testClasses/parseFolderPath.php');
//require_once('../../../tests/testClasses/parseLicenseTbl.php');
require_once('../../../tests/testClasses/dom-parseLicenseTable.php');

global $URL;

class verifyFossolyTest extends fossologyTestCase
{
	public $mybrowser;
	public $host;

	function setUp()
	{
		/*
		 * This test requires that the fossology test archive has been
		 * loaded under the name fossarchive-T.tar.bz2 For now, the setup
		 * will just verify the material is there?
		 */
		global $URL;
		global $name;
		global $safeName;

		$name = 'fossI16L518.tar.bz2';
		$safeName = escapeDots($name);
		$this->host = getHost($URL);

		$this->Login();

		/* check for existense of archive */
		$page = $this->mybrowser->get($URL);
		$page = $this->mybrowser->clickLink('Browse');
		$this->assertTrue($this->myassertText($page, '/Browse/'),
     "verifyFossI16L518 FAILED! Could not find Browse menu\n");
		$page = $this->mybrowser->clickLink('Testing');
		$this->assertTrue($this->myassertText($page, '/Testing/'),
     "verifyFossI16L518 FAILED! Could not find Testing folder\n");
		$result = $this->myassertText($page, "/$safeName/");
		if(!($result)) { exit(FALSE); }
	}

	function testVerifyFossI16L518()
	{
		global $URL;
		global $name;
		global $safeName;

		// licenseCounts recorded 20100-03-10 for 1.2 release with nomos.
		$licenseCounts = array(
											'GPL_v2'     					=> 225,
    									'(C)HP-Dev' 					=> 41,
    									'No License Found' 	=> 28,
    									'GPL' 								=> 24,
    									'LGPL_v2.1'						=> 17,
    									'Apache_v2.0' 				=> 2,
    									'(C)IETF' 						=> 2,
    									'GFDL' 								=> 2,
    									'Public-domain-claim' => 2,
    									'APSL_v2.0' 					=> 1,
    									'Artistic' 						=> 1,
    									'Boost' 							=> 1,
    									'BSD' 								=> 1,
    									'FSF-possibility'			=> 1,
    									'GPL_v2.1+' 					=> 1,
                      'GPL_v3' 							=> 1,
                      'Indemnity' 					=> 1,
                      'LGPL_v2.1+' 					=> 1,
                      'LGPL_v3+' 						=> 1,
                      'Misc-Copyright' 			=> 1,
    									'NoLicenseFound' 			=> 1,
        						  'NPL' 								=> 1,
                      'OSL_v3.0' 						=> 1,
                      'PHP-possibility' 		=> 1,
											'Possible-copyright' 	=> 1,
                      'Python' 							=> 1,
                      'See-doc(OTHER)' 			=> 1,
                      'X11-possibility' 		=> 1,
                     	'Zope' 								=> 1,

		);

		print "starting VerifyFossI16L518 test\n";
		$page = $this->mybrowser->clickLink('Browse');
		$this->assertTrue($this->myassertText($page, '/Browse/'),
             "verifyFossI16L518 FAILED! Could not find Browse menu\n");
		/* Testing folder */
		$page = $this->mybrowser->clickLink('Testing');
		//print "************ Page after upload link *************\n$page\n";
		$this->assertTrue($this->myassertText($page, "/Browse/"),
       "verifyFossI16L518 FAILED! Browse Title not found\n");
		$this->assertTrue($this->myassertText($page, "/$safeName/"),
       "verifyFossI16L518 FAILED! did not find $name\n");
		$this->assertTrue($this->myassertText($page, "/>View</"),
       "verifyFossI16L518 FAILED! >View< not found\n");
		$this->assertTrue($this->myassertText($page, "/>Meta</"),
       "verifyFossI16L518 FAILED! >Meta< not found\n");
		$this->assertTrue($this->myassertText($page, "/>Download</"),
       "verifyFossI16L518 FAILED! >Download< not found\n");

		/* Select archive */
		$page = $this->mybrowser->clickLink($name);
		//print "************ Page after select foss archive *************\n$page\n";
		$this->assertTrue($this->myassertText($page, "/fossology\//"));

		/* Select fossology link */
		$page = $this->mybrowser->clickLink('fossology/');

		/* need to check that there are 16 items */
		/* check that all the [xxx] items add to 519 */

		$this->assertTrue($this->myassertText($page, "/Makefile/"));
		$this->assertTrue($this->myassertText($page, "/mkcheck\.sh/"),
                      "FAIL! did not find mkcheck.sh\n");
		$this->assertTrue($this->myassertText($page, "/>View</"),
                      "FAIL! >View< not found\n");
		$this->assertTrue($this->myassertText($page, "/>Meta</"),
                      "FAIL! >Meta< not found\n");
		$this->assertTrue($this->myassertText($page, "/>Download</"),
                      "FAIL! >Download< not found\n");

		/* Select the License link to View License Historgram */
		$browse = new parseBrowseMenu($page);
		$mini = new parseMiniMenu($page);
		$miniMenu = $mini->parseMiniMenu();
		$url = makeUrl($this->host, $miniMenu['Nomos License']);
		if($url === NULL) { $this->fail("verifyFossI16L518 Failed, host/url is not set"); }

		$page = $this->mybrowser->get($url);
		//print "page after get of $url is:\n$page\n";
		$this->assertTrue($this->myassertText($page, '/Nomos License Browser/'),
          "verifyFossI16L518 FAILED! Nomos License Browser Title not found\n");
		$this->assertTrue($this->myassertText($page, '/Total licenses: 335/'),
        "verifyFossI16L518 FAILED! Total Licenses does not equal 335\n");
		$this->assertTrue($this->myassertText($page, '/Unique licenses: 29/'),
        "verifyFossI16L518 FAILED! Unique Licenses does not equal 29\n");

		// get the license names and 'Show' links
		$licHistogram = new domParseLicenseTbl($page);
		// create list of Show urls
		$urls = array();
		foreach ($licHistogram->hList as $license) {
			$urls[$license['textOrLink']] = makeUrl($this->host, $license['showLink']);
		}
		if(empty($urls)) {
			$this->fail("FATAL! no urls to process, there should be many for"
			. " this test, Stopping the test");
			return;
		}
		// verify every row against the standard
		foreach($urls as $lic => $showUrl){
			$page = $this->mybrowser->get($showUrl);
			print "Checking the number of files based on $lic\n";
			if($licenseCounts[$lic] > 50) {
				$this->assertTrue($this->myassertText($page, '/225 files found with this license/'),
        "verifyFossI16L518 FAILED! Licenses found for $lic does not equal 225\n");
				continue;
			}
			$licFileList = new parseFolderPath($page, $URL);
			$fileCount = $licFileList->countFiles();
			$this->assertEqual($fileCount, $licenseCounts[$lic],
    	"verifyFossI16L518 FAILED! Should be $licenseCounts[$lic] files
    	 based on $lic got:$fileCount\n");
		}
	}
}
?>
