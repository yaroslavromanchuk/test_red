<?php 
class IMAP {

public $do_debug = 2;

  /**
   * POP3 Mail Server
   * @var string
   */
  public $host;

  /**
   * POP3 Port
   * @var int
   */
  public $port;

  /**
   * POP3 Timeout Value
   * @var int
   */
  public $tval;

  /**
   * POP3 Username
   * @var string
   */
  public $username;

  /**
   * POP3 Password
   * @var string
   */
  public $password;

  /////////////////////////////////////////////////
  // PROPERTIES, PRIVATE AND PROTECTED
  /////////////////////////////////////////////////

  private $pop_conn;
  private $connected;
  private $error;     //  Error log array
  
    public function __construct() {
    $this->pop_conn  = 0;
    $this->connected = false;
    $this->error     = null;
  }
   public function Authorise ($host = '', $port = false, $tval = false, $username ='', $password='', $debug_level = 0) {
 
		//$this->host = $this->host;

    //  If no port value is passed, retrieve it
   
     // $this->port =$this->port;
  

    //  If no port value is passed, retrieve it
  
     // $this->tval = $this->tval;
 

    //$this->do_debug = $this->debug_level;
   // $this->username = $this->username;
    //$this->password = $this->password;

    //  Refresh the error log
    $this->error = null;

    //  Connect
    $result = $this->Connect($this->host, $this->port, $this->tval);

    if ($result) {
      $login_result = $this->Login($this->username, $this->password);

      if ($login_result) {
        $this->Disconnect();

        return true;
      }

    }

    //  We need to disconnect regardless if the login succeeded
    $this->Disconnect();

    return false;
	}
  
   public function Connect ($host, $port = false, $username, $password) {
   //$mail = imap_open('{mail.server.com:143}', 'username', 'password');
    //  Are we already connected?
    if ($this->connected) {
      return true;
    }
	
	// set_error_handler(array(&$this, 'catchWarning'));

    //  Connect to the POP3 server
    $this->pop_conn = fsockopen($host,    //  POP3 Host
                  $port,    //  Port #
                  $errno,   //  Error Number
                  $errstr,  //  Error Message
                  $tval);   //  Timeout (seconds)

    //  Restore the error handler
   // restore_error_handler();

    //  Does the Error Log now contain anything?
    if ($this->error && $this->do_debug >= 1) {
      $this->displayErrors();
    }
	if ($this->pop_conn == false) {
      //  It would appear not...
      $this->error = array(
        'error' => "Failed to connect to server $host on port $port",
        'errno' => $errno,
        'errstr' => $errstr
      );

      if ($this->do_debug >= 1) {
        $this->displayErrors();
      }

      return false;
    }
	}
	
	public function Login ($username = '', $password = '') {
    if ($this->connected == false) {
      $this->error = 'Not connected to POP3 server';

      if ($this->do_debug >= 1) {
        $this->displayErrors();
      }
    }

    if (empty($username)) {
      $username = $this->username;
    }

    if (empty($password)) {
      $password = $this->password;
    }

    $pop_username = "USER $username" . $this->CRLF;
    $pop_password = "PASS $password" . $this->CRLF;

    //  Send the Username
    $this->sendString($pop_username);
    $pop3_response = $this->getResponse();

    if ($this->checkResponse($pop3_response)) {
      //  Send the Password
      $this->sendString($pop_password);
      $pop3_response = $this->getResponse();

      if ($this->checkResponse($pop3_response)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Disconnect from the POP3 server
   * @access public
   */
  public function Disconnect () {
    $this->sendString('QUIT');

    fclose($this->pop_conn);
  }

  /////////////////////////////////////////////////
  //  Private Methods
  /////////////////////////////////////////////////

  /**
   * Get the socket response back.
   * $size is the maximum number of bytes to retrieve
   * @access private
   * @param integer $size
   * @return string
   */
  private function getResponse ($size = 128) {
    $pop3_response = fgets($this->pop_conn, $size);

    return $pop3_response;
  }

  /**
   * Send a string down the open socket connection to the POP3 server
   * @access private
   * @param string $string
   * @return integer
   */
  private function sendString ($string) {
    $bytes_sent = fwrite($this->pop_conn, $string, strlen($string));

    return $bytes_sent;
  }

  /**
   * Checks the POP3 server response for +OK or -ERR
   * @access private
   * @param string $string
   * @return boolean
   */
  private function checkResponse ($string) {
    if (substr($string, 0, 3) !== '+OK') {
      $this->error = array(
        'error' => "Server reported an error: $string",
        'errno' => 0,
        'errstr' => ''
      );

      if ($this->do_debug >= 1) {
        $this->displayErrors();
      }

      return false;
    } else {
      return true;
    }

  }

  /**
   * If debug is enabled, display the error message array
   * @access private
   */
  private function displayErrors () {
    echo '<pre>';

    foreach ($this->error as $single_error) {
      print_r($single_error);
    }

    echo '</pre>';
  }

  /**
   * Takes over from PHP for the socket warning handler
   * @access private
   * @param integer $errno
   * @param string $errstr
   * @param string $errfile
   * @param integer $errline
   */
  private function catchWarning ($errno, $errstr, $errfile, $errline) {
    $this->error[] = array(
      'error' => "Connecting to the POP3 server raised a PHP warning: ",
      'errno' => $errno,
      'errstr' => $errstr
    );
  }

  //  End of class

}

?>