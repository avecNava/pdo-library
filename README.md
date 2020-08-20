# SQLITE CRUD operations using PHP PDO library

## Introduction 


The **PHP Data Objects (PDO)** extension defines a lightweight, consistent interface for accessing databases in PHP. PDO provides a data-access abstraction layer, which means that, regardless of which database you're using, you use the same functions to issue queries and fetch data.

This library uses SQLITE file as database. The CRUD operations are based on a table called address_book. The table has the following fields.

Table name : **address_book**

**Schema** :
|field name | data type | other | remarks |
|:---|:---|:---|:---|
| id | INTEGER | PRIMARY KEY, AUTOINCREMENT | primary key |
| fname | TEXT | NOT NULL | first name |
| lname | TEXT | NOT NULL | last name |
| gender | TEXT | | gender (M/F) |
| dob | TEXT | | date of birth (YYYY-MM-DD) |
| phone | TEXT | | contact number |
| email | TEXT | | email address |
| addr | CHAR(100) | | address |
| created_at | TEXT | default CURRENT_TIMESTAMP | record creation date |
| updated_at | TEXT | | record update date |

<br>

## CREATE table

### Create table to store the data. It shall only create a new table if it doesn't exist

```PHP
<?php
    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();

    //create table(s)
    $addr_book->initialize_table();
?>
```

## CREATE record(s)

### Insert records. The inputs are suplied as array

```PHP

<?php
    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();

    //prepare array of records
    $records = array(
        array('fname' => 'Louetta',
            'lname' => 'Laven',
            'dob' => '1981-11-23',
            'gender' => 'F',
            'phone' => '06416121212',
            'email' => 'Louetta@hey.com',
            'addr' => 'Thailand, Bangkok'
        ),
        array('fname' => 'Valerie',
            'lname' => 'Saxton',
            'dob' => '2013-06-25',
            'gender' => 'F',
            'phone' => '9841615245',
            'email' => 'Valarie@yahoo.com',
            'addr' => 'Lalitpur, Nepal'
        ),
        array('fname' => 'Madlyn',
            'lname' => 'Sweeny'
            'gender' => 'F',
            'dob' => '2007-08-14',
            'phone' => '98510658975',
            'email' => 'Sweeny@outlook.com',
            'addr' => 'Pokhara, Nepal'
        ),
        array('fname' => 'Luciana',
            'lname' => 'Stelly',
            'gender' => 'F',
            'dob' => '1983-09-07',
            'phone' => '9802356984',
            'email' => 'Mccants@gmail.com',
            'addr' => 'Los Angeles, USA'
        ),
    );

    //insert new record
    $addr_book->insert_data($records);
    echo $addr_book->message;
?>
```

## UPDATE record

### Update records. The records to be updated are supplied as array. id refers to the row being updated with the new record

```PHP
<?php
    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();


    $id =2;         //existing record to be updated

    //new record to be updated
    $new_record=array(
        'fname' => 'Achyut',
        'lname' => 'Ghimire',
        'gender' => 'M',
        'dob' => '1980-2-15',
        'phone' => '9841898989',
        'email' => 'achyut.ghimire@gmail.com',
        'addr' => 'Bhaktapur, Nepal'
    );

    //update record
    $addr_book->update_record($id, $new_record);
    echo $addr_book->message;
```

## READ record(s)

### Show only records with matching keywords. Typically used in search operaions

```PHP
    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();

    //querying records (based on given keywords)
    $keyword = 'Taylor';
    $records = $addr_book->query_record($keyword);

    $count = 0;
    //loop through the records array and print the result
    foreach ($records as $row) {
        $count++;
        echo '<ul>';
        echo "<li> { $count } . </li>";
        echo "<li> { $row['fname'] }  {$row['lname'] } </li>";
        echo "<li> { $row['gender'] } </li>";
        echo "<li> { $row['dob'] } </li>";
        echo "<li> { $row['phone'] } </li>";
        echo "<li> { $row['email'] } </li>";
        echo "<li> { $row['addr'] } </li>";
        echo '</ul>';
        echo "<div class='separator'></div>";
     }
```

### Show all records

``` PHP
<?php
    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();

    //show records  
    $records = $addr_book->show_records();
    echo $addr_book->message;

    $count = 0;
    //loop through the records array and print the result
    foreach ($records as $row) {
        $count++;
        echo '<ul>';
        echo "<li> { $count } . </li>";
        echo "<li> { $row['fname'] }  {$row['lname'] } </li>";
        echo "<li> { $row['gender'] } </li>";
        echo "<li> { $row['dob'] } </li>";
        echo "<li> { $row['phone'] } </li>";
        echo "<li> { $row['email'] } </li>";
        echo "<li> { $row['addr'] } </li>";
        echo '</ul>';
        echo "<div class='separator'></div>";
     }
?>
```

## DELETE record

### Delete the record with the given id

```PHP
<?php

    //instantiate the class
    require_once('PDOlibrary.php');
    $addr_book = new AddressBook();

    //delete operaion (on the basis of record id)

    $id = 1;
    $addr_book->delete_record($id);

    echo $addr_book->message;

?>
```
