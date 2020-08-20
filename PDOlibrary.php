<?php
class AddressBook
{
   
    private string $connect_str;
    public string $error;
    public string $message;
    private $conn;
    
    //public const CONNECT_STR_MYSQL = 'mysql:host=$servername;dbname=$dbname", $username, $password';   
    
    function __construct(){

        // Set default timezone
        date_default_timezone_set('UTC');

        $this->error='';
        $this->message='';

        //update the connection string to use other database eg, MariaDB
        $this->connect_str = 'sqlite:address-book.sqlite3';

        if($this->connect() != 'SUCCESS'){
            $this->message = 'Could not connect to the database';
            return;
        }

    }

    function connect(){
        try {

            $this->conn = new PDO($this->connect_str);
            
            //set error mode to exceptions
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->message = 'Successfully connected to the database';
            return 'SUCCESS';
            
        }
        catch(PDOException $e) {
            $this->error = $e->getMessage();            
        }
    }

    public function initialize_table(){
        $sql = "CREATE TABLE 
                IF NOT EXISTS address_book(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    fname TEXT NOT NULL,
                    lname TEXT NOT NULL,
                    gender TEXT ,
                    dob TEXT,
                    phone TEXT,
                    email TEXT,
                    addr CHAR(100),
                    created_at TEXT default CURRENT_TIMESTAMP,
                    updated_at TEXT
                )";

        try {
            $this->conn->exec($sql);
            $this->message = 'Table address_book initialized<br/>' . $sql ;
            return 'SUCCESS';
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function insert_data($records){
        $sql = "INSERT INTO address_book (fname, lname, gender, dob, phone, email, addr)
                VALUES (:fname, :lname, :gender, :dob, :phone, :email, :addr)";
        $stmt = $this->conn->prepare($sql);

        //bind parameters
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':addr', $addr);

        try {

            foreach ($records as $record) {
                $fname = $record['fname'];
                $lname = $record['lname'];
                $gender = $record['gender'];
                $dob = $record['dob'];
                $phone = $record['phone'];
                $email = $record['email'];
                $addr = $record['addr'];
                $stmt->execute();
            }

            $this->message = "Successfully inserted {$stmt->rowCount()} records";

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }        
    }

    public function update_record($id, $record){
        $sql = "UPDATE address_book 
                SET fname = :fname, lname = :lname, gender = :gender, dob = :dob, phone = :phone, 
                email = :email, addr = :addr, updated_at = :updated_at 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $date = date('Y-m-d H:i:s');

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fname', $record['fname']);
        $stmt->bindParam(':lname', $record['lname']);
        $stmt->bindParam(':gender', $record['gender']);
        $stmt->bindParam(':dob', $record['dob'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $record['email']);
        $stmt->bindParam(':phone', $record['phone']);
        $stmt->bindParam(':addr', $record['addr']);
        $stmt->bindParam(':updated_at', $date, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $this->message = "Successfully updated {$stmt->rowCount()} records";
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }


    public function query_record($keyword){
        
        $sql = "SELECT * FROM address_book 
                WHERE fname LIKE :fname OR lname LIKE :lname 
                OR phone LIKE :phone OR email LIKE :email 
                OR addr LIKE  :addr";
        
        $stmt = $this->conn->prepare($sql);
        $keyword = $keyword . '%';
        //With bindParam, you can only pass variables ; not values
        //with bindValue, you can pass both (values, obviously, and variables)
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':fname', $keyword);
        $stmt->bindParam(':lname', $keyword);
        $stmt->bindParam(':phone', $keyword);
        $stmt->bindParam(':email', $keyword);
        $stmt->bindParam(':addr', $keyword);

        try {
            $stmt->execute();
            $this->message = "Got some records";
            return $stmt->fetchAll();

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public function show_records(){
        $sql = "SELECT * FROM address_book ORDER BY updated_at DESC, lname, fname";
        //https://stackoverflow.com/questions/883365/row-count-with-pdo
        /*For most databases, PDOStatement::rowCount() does not return the number of rows affected by a SELECT statement. 
        Instead, use PDO::query() to issue a SELECT COUNT(*) statement with the same predicates as your intended SELECT statement, 
        then use PDOStatement::fetchColumn() to retrieve the number of rows that will be returned. Your application can then perform the correct action.*/
        $nRows = $this->conn->query('SELECT COUNT(*) FROM address_book')->fetchColumn();

        try {
            //If there are no variables going to be used in the query, we can use a conventional query() method instead of prepare and execute.
            $stmt = $this->conn->query($sql);            
            $this->message = "Got {$nRows} records";
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function delete_record($id){
        try {
            $stmt = $this->conn->prepare("DELETE FROM address_book WHERE id = ?");
            $stmt->execute(array($id));
            $this->message = "Deleted {$stmt->rowCount()} records";
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}
?>