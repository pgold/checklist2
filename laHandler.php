<?php
class LaHandler {
  public function __construct() {
    define('USERNAME', 'TestePUC');
    define('PASSWORD', 'pucteste');

    define('USER_AGENT',
           'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.04 (lucid) Firefox/3.6.17');
    define('LOGIN_URL',
           'http://livearchive.onlinejudge.org/index.php?option=com_comprofiler&task=login');
    define('LIST_URL_PREFIX',
           'http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&page=show_authorstats&userid=');
    define('PROBLEM_URL_PREFIX',
           'http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&page=show_problem&problem=');

    libxml_use_internal_errors(true);
  }

  private function logIn() {
    $ch = curl_init();
    $postFields = array('username'      => USERNAME,
                        'passwd'        => PASSWORD,
                        'op2'           => 'login',
                        'lang'          => 'english',
                        'force_session' => '1',
                        'message'       => '0',
                        'loginfrom'     => 'loginform',
                        'remember'      => 'yes',
                        'Submit'        => 'Login',
                        );

    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, LOGIN_URL);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);

    $result = curl_exec($ch);
    preg_match_all('|Set-Cookie: (.*);|U', $result, $matches);
    $this->cookies = implode(';', $matches[1]);

    curl_close($ch);
  }

  private function getSolvedProblemsForUser($userid) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, LIST_URL_PREFIX . $userid);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);

    $dom = new DOMDocument();
    $dom->loadHTML(curl_exec($ch));
    curl_close($ch);
    $xpath = new DOMXPath($dom);

    if($xpath->query("//span[@id='mod_login_greeting']")->length == 0) {
      // Não estamos logados. Qual o tratamento de erro adequado, dado que
      // a gente *acabou* de logar?
      assert(false);
    }

    $nodeList = $xpath->query("//td[@class='maincontent']/table[2]/tr");

    $ret = array();
    foreach($nodeList as $node) {
      $class = $node->attributes->getNamedItem("class")->value;
      if($class != "sectiontableentry1" && $class != "sectiontableentry2")
        continue;

      $td = $node->firstChild;
      assert($td->nodeName == "td");

      $ret[$td->textContent] =
        strtotime($node->childNodes->item(6)->textContent);
    }

    return $ret;
  }

  public function getSolvedProblemsForUsers($users) {
    $this->logIn();
    $problems = array_map(array($this, "getSolvedProblemsForUser"), $users);

	if(0 == count($users)) return array();
	else return array_combine($users, $problems);
  }

  public function getProblemURL($problemId) {
  	return PROBLEM_URL_PREFIX.($problemId-1999);
  }

  public function getUserURL($userId) {
  	return LIST_URL_PREFIX.$userId;
  }
}
?>
