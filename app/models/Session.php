<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

/* 
    Database Sessions

    Requirements:
    Create the following mysql table in your database if you are
    implementing this Session Model.

    CREATE TABLE sessions ( 
        session_id CHAR(32) NOT NULL, 
        session_data TEXT NOT NULL, 
        session_lastaccesstime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        PRIMARY KEY (session_id)
    );
*/

class Session extends Model
{
    private $db;

    public function __construct()
    {
        parent::__construct();

        // Instantiate new Database object
        $this->db = new Model();

        // Set handler to overide SESSION
		session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
            );
            
            // The following prevents unexpected effects when using objects as save handlers
            register_shutdown_function('session_write_close');

            // Set the current session id from the users browser $_COOKIE["PHPSESSID"]
            if( array_key_exists("PHPSESSID", $_COOKIE) )
            {
                session_id($_COOKIE["PHPSESSID"]);
            } else {
                session_id($this->makeSessionId());
            }

            // This sets a persistent cookie that lasts a day.
            session_start([
                'cookie_lifetime' => 86400,
            ]);

            // Proceed to set and retrieve values by key from $_SESSION
            // $_SESSION['my_key'] = 'some value';
            // $my_value = $_SESSION['my_key'];
    }

    /*
    Opening the Session - The first stage the session goes through 
    is the opening of the session file. Here you can perform any 
    action you like; the PHP documentation indicates that this function 
    should be treated as a constructor, so you could use it to initialize 
    class variables if you’re using an OOP approach.
    */
    function open($path, $name) 
    {
        $sql = "INSERT INTO sessions SET session_id = :session_id" . 
                ", session_data = '' ON DUPLICATE KEY UPDATE session_lastaccesstime = NOW()";

        $bind = [
            ':session_id' => session_id(),
            ];

        $this->db->run($sql, $bind); 

        return true;
    }

    /*
    Immediately after the session is opened, the contents of the session 
    are read from whatever store you have nominated and placed into the $_SESSION array.
    It is important to understand that this data is not pulled every time you access a 
    session variable. It is only pulled at the beginning of the session life cycle when 
    PHP calls the open callback and then the read callback.
    */
    function read($session_id) 
    {
        $sql = "SELECT session_data FROM sessions where session_id = :session_id";

        $bind = [
            ':session_id' => $session_id,
            ];

        $data = $this->db->run($sql, $bind);

        // php 7.1 and above strictly requires the session read to return a string and not even a null value.
        if(empty($data[0]['session_data'])) {
            return '';
        } else {
            return $data[0]['session_data'];
        }
    }

    /*
    Writing the data back to whatever store you’re using occurs either at the end of 
    the script’s execution or when you call session_write_close().
    */
    function write($session_id, $session_data) 
    { 
        $sql = "INSERT INTO sessions SET session_id = :session_id" . 
                ", session_data = :session_data" . 
                ", session_lastaccesstime = NOW()" . 
                " ON DUPLICATE KEY UPDATE session_data = :session_data";

        $bind = [
            ':session_id' => $session_id,
            ':session_data' => $session_data,
            ];

        $this->db->run($sql, $bind);

        return true;
    }

    /*
    Closing the session occurs at the end of the session life cycle, 
    just after the session data has been written. No parameters are 
    passed to this callback so if you need to process something here 
    specific to the session, you can call session_id() to obtain the ID.
    */
    function close() 
    {
        $this->session_id = session_id();

        return true;
    }

    /*
    Destroying the session manually is essential especially when using sessions 
    as a way to secure sections of your application. The callback is called when 
    the session_destroy() function is called.

    In its default session handling capability, the session_destroy() function will 
    clear the $_SESSION array of all data. The documentation on php.net states that 
    any global variables or cookies (if they are used) will not cleared, so if you 
    are using a custom session handler like this one you can perform these tasks in 
    this callback also.
    */
    function destroy($session_id) 
    {
        $sql = "DELETE FROM sessions WHERE session_id = :session_id"; 

        $bind = [
            ':session_id' => $session_id,
            ];
        
        $this->db->run($sql, $bind);
    
        setcookie(session_name(), "", time() - 3600);

        return true;
    }

    /*
    Garbage Collection - The session handler needs to cater to the fact that the 
    programmer won’t always have a chance to manually destroy session data. 
    For example, you may destroy session data when a user logs out and it is no 
    longer needed, but there’s no guarantee a user will use the logout functionality 
    to trigger the deletion. The garbage collection callback will occasionally be 
    invoked by PHP to clean out stale session data. The parameter that is passed 
    here is the max lifetime of the session which is an integer detailing the 
    number of seconds that the lifetime spans.
    */
    function gc($lifetime) 
    {
        $sql = "DELETE FROM sessions WHERE session_lastaccesstime < DATE_SUB(NOW(), INTERVAL " . $lifetime . " SECOND)";
        
        $this->db->run($sql);

        return true;
    }

    /*
    Generate a genesis Session ID. 
    Called by session->open if there is no session cookie found.
    */
    function makeSessionId()
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz0123456789');
        $rand_id = '';
        shuffle($seed);
        foreach (array_rand($seed, 32) as $k) { // sessions ids are 32 chars in length.
            $rand_id .= $seed[$k];
        }
           return $rand_id; 
    }


    /*
    Simple debugger for dumping all the Session data from the database.
    */
    function getSessionData()
    {
        $data = $this->db->select('sessions');

        return $data;
    }

}