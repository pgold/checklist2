<?php
class SpojHandler {
  public function __construct() {
    define('USER_AGENT',
           'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17');
    define('USER_URL',
           'http://br.spoj.pl/users/%s/');
    define('PROBLEM_URL',
           'http://br.spoj.pl/problems/%s/');

    libxml_use_internal_errors(true);
  }

  private function getSolvedProblemsForUser($userid) {
    $dom = new DOMDocument();
    $dom->loadHTML(file_get_contents(sprintf(USER_URL, $userid)));
    $xpath = new DOMXPath($dom);

	$nodeList = $xpath->query("//table[@align='center']//td/a[starts-with(@href,'/status/')]");
    $ret = array();
    foreach($nodeList as $node) {
		$href = $node->getAttribute( 'href' );
		if(preg_match("/\/status\/(.+),.*/", $href, $problem)) {
			$ret[$problem[1]] = true;
		}
    }

    return $ret;
  }

  public function getSolvedProblemsForUsers($users) {
    $problems = array_map(array($this, "getSolvedProblemsForUser"), $users);

	if(0 == count($users)) return array();
	else return array_combine($users, $problems);
  }

  public function getProblemURL($problemId) {
  	return sprintf(PROBLEM_URL, $problemId);
  }

  public function getUserURL($userId) {
  	return sprintf(USER_URL, $userId);
  }
}
?>
