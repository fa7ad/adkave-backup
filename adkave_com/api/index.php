<?php
/**
 * Adkave backend API
 * @author Fahad Hossain<8bit.demoncoder@gmail.com>
 * @license Proprietary EULA
 * Date: 5th April 2015
 * Time: 20:31 PM UTC+6000
 */
header("Content-Type: application/json");
header("charset=utf-8");
header("Access-Control-Allow-Origin: *");
// Secret
define('SITE_SECRET', "Mk500xY");
if (isset($_GET['new_kave']) && !empty($_GET['new_kave'])) {
    $db = new Database();
    $db->create_client(trim($_GET['new_kave']));
} else {
    new Adkave();
}

class Adkave
{
    private $db;                        /* the database obj */
    private $errors = array();  /* holds error messages */

    /**
     * Prepares the class for explosion xP
     */
    public function __construct()
    {
        $this->db = new Database();
        if (isset($_POST['secret'])) {
            if ($_POST['secret'] == SITE_SECRET) {
                $this->process_new_record();
/* 				$this->mail_to_client(); */
            } else {
                $this->set_message('api', 'Sorry', 'This site is not allowed to use our API.');
                $this->output_json($this->errors);
            }
        } else {
            $this->set_message('api', 'Sorry', 'This site is not allowed to use our API.');
            $this->output_json($this->errors);
        }
    }

    /**
     * The actual process of adding a new record
     */
    public function process_new_record()
    {

        $name = trim($_POST['name']);
        $contact = trim($_POST['contact']);
        $ttc = trim($_POST['ttc']);
        $comment = trim($_POST['comment']);
		// Client name
		if (isset($_POST['client_name'])){
			$client = $_POST['client_name'];
		} elseif (isset($_GET['new_kave'])){
			$client = $_POST['new_kave'];
		} else {
			$client = 'business';
		}
		$client_email = $client . '@adkave.com';
        /* Errors exist */
        if (
            $this->try_error($name, 'name') == false ||
            $this->try_error($contact, 'contact') == false ||
            $this->try_error($ttc, 'time') == false ||
            $this->try_error($comment, 'comment') == false
        ) {
            $this->set_message('input', 'Error', 'Invalid input value(s). Please try again');
            $this->output_json($this->errors);
        } else {
            if (($ret = $this->db->db_new_record($name, $contact, $ttc, $comment)) > 0) {
				$email = $this->prep_mail($name, $contact, $ttc, $comment);
				mail($client_email, "New Submission on your adkave site", $email);
                $this->set_message('done', 'Thank You!', 'We will call you back.');
            } else {
                $this->set_message('sql', 'Sorry', 'We apologize for this inconvenience');
            }
            $this->output_json($this->errors);
        }
    }

    /**
     * Checks input values against our preset rules.
     * @param string $value value to check
     * @param string $type type of field
     * @return bool
     */
    private function try_error($value, $type)
    {
        switch ($type) {
            case "name":
                if (strlen($value) >= 25) {
                    $this->set_message('name', 'Error', "Your name is too long");
                    return false;
                } else {
                    return true;
                }
                break;
            case "contact":
                if (!is_numeric($value)) {
                    if (strlen($value) > 25) {
                        $this->set_message(
                            'contact',
                            'Error',
                            'The contact info you provided is neither a number nor an e-mail'
                        );
                        return false;
                    }
                } elseif (is_numeric($value) || strlen($value) <= 45) {
                    return true;
                }
                break;
            case "time":
                if ($value == "morning"
                    || $value == "evening"
                    || $value == "afternoon"
                    || $value == "any"
                ) {
                    return true;
                } else {
                    $this->set_message('time', 'Error', 'Invalid preferred time.');
                    return false;
                }
                break;
            case "comment":
                return true;
                break;
            default:
                $this->set_message('input', 'Error', "$type is not a valid input type for our API");
                return false;
                break;
        }
        return true;
    }

    /**
     * Add an error message and the relevant key
     * @param $key string the key field for the message
     * @param $heading string Heading
     * @param $message string the message
     */
    private function set_message($key, $heading, $message)
    {
        $this->errors[$key] = array(
            'heading' => $heading,
            'message' => $message
        );
    }

    /**
     * Encodes the errors array in a JSON object
     * @param array $layered_array
     */
    private function output_json($layered_array)
    {
        $counter = 0;
        $new_array = [];
        foreach ($layered_array as $key => $layer) {
            $new_array[$counter] = $layer;
            $counter++;
        }
        echo json_encode($new_array);
    }
	
	/**
	 * Prepare the email
	 * @param string $name
	 * @param string $contact
	 * @param string $ttc
	 * @param string $comment
	 */
	private function prep_mail($name, $contact, $ttc, $comment){
		
	}
}

class Database
{
    private $link;
    private $db_data;

    /**
     * Preparing for launch
     */
    public function __construct()
    {
        $this->db_data = $this->create_db_config();
        $this->link = mysqli_connect(
            $this->db_data['host'],
            $this->db_data['username'],
            $this->db_data['password'],
            $this->db_data['database']
        );
        if (mysqli_connect_errno()) {
            $this->run_exception();
        }
    }

    /**
     * Creates DB config array
     * @return array|bool returns the array or false
     */
    private function create_db_config()
    {
        $new_kave = isset($_GET['new_kave']) ? $_GET['new_kave'] : 'nogorads';
        return $db = [
            'host' => 'localhost',
            'username' => 'xarthcdz_ngads',
            'password' => 'm3m3dotcom',
            'database' => 'xarthcdz_nogorads',
            'table' => isset($_POST['client_name']) ? $_POST['client_name'] : $new_kave
        ];
    }
    /**
     * Fatal error, i.e. unauthorized access
     */
    private function run_exception()
    {
        $error = array(
            'heading' => 'Error',
            'message' => 'This page is not allowed to use our API'
        );
        $errors = array(
            0 => $error
        );
        echo json_encode($errors);
    }

    /**
     * on finish, close the sql connection
     */
    public function __destruct()
    {
        mysqli_close($this->link);
    }

    /**
     * @param string $name Customer's name
     * @param string $contact Customer's contact info
     * @param string $ttc Time to chat
     * @param string $comment
     * @return int > 0 if successful
     */
    public function db_new_record($name, $contact, $ttc, $comment)
    {
        $name = mysqli_real_escape_string($this->link, $name);
        $contact = mysqli_real_escape_string($this->link, $contact);
        $ttc = mysqli_real_escape_string($this->link, $ttc);
        $comment = mysqli_real_escape_string($this->link, $comment);

        $table = $this->db_data['table'];
        mysqli_autocommit($this->link, FALSE);

        $query = "INSERT INTO " . $table . "(name,contact,ttc,comment)
				  VALUES('$name','$contact','$ttc','$comment')";
        mysqli_query($this->link, $query);

        if (mysqli_errno($this->link))
            return -1;
        else {
            mysqli_commit($this->link);
            return 1;
        }
    }

    /**
     * Create a table for a new client
     * @param $client_name string Name of the client
     */
    public function create_client($client_name)
    {
        $client_name = mysqli_real_escape_string($this->link, $client_name);
        mysqli_autocommit($this->link, false);
        $sql = "CREATE TABLE IF NOT EXISTS $client_name (
                time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                product varchar(255) DEFAULT NULL,
                quantity int(11) NOT NULL,
                name varchar(255) NOT NULL,
                contact varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        mysqli_query($this->link, $sql);
        mysqli_commit($this->link);
    }
}