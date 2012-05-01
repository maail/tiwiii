<?php

/**
 * connectDB
 *
 * Loads the db connection data from config.php and 
 * connects to the db.
 * 
 * @author Maail
 *
 */


require_once('config.php'); 

class Connect {

	private $dbName; 
	private $host; 
	private $userName;
	private $password;
	private $conn;

    public function __construct(){
    
        $this->dbName 	= NULL;
        $this->host 	= NULL; 
        $this->userName = NULL; 
        $this->password = NULL;
        $this->conn 	= NULL;
        
        //echo "Constructor<br />";
        $this->loadDb();
    }
    
    private function loadDB(){
        
        $this->host 	 = HOST; 
        $this->dbName 	 = DATABASE;
        $this->userName  = USERNAME; 
        $this->password  = PASSWORD;
         
        //echo "Database details loaded<br />";
        $this->openCon();
            
	}
   
    private function openCon(){
        
        $this->conn = mysql_connect($this->host,$this->userName,$this->password);
        $this->open = mysql_select_db($this->dbName,$this->conn);
        try
        {
        	if(!($this->open))
        	{
        	   throw new Exception("Please check your database connection details.");
        	   
			}
        	else
        	{
        		//echo "Connected to database<br />";
        		
        	}
        	
        }
        catch(Exception $e)
        {
        	echo $e->getMessage();
        
        } 
                   
    }
    
    public function closeCon(){
        
        $this->conn  = mysql_connect($this->host,$this->userName,$this->password);
        $this->close = mysql_close($this->conn);
        try
        {
        	if(!($this->close))
        	{
        	  throw new Exception("Database connection could not be closed");
        	   
    		}
        	else
        	{
        		//echo "Database connection closed<br />";
        	}
        	
        }
        catch(Exception $e)
        {
        	echo $e->getMessage();
        
        } 
                   
    }
}
?>