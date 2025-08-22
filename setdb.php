<!-- Creating the database connection -->
<?php
    include_once 'login_check.php';
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'mbhdbase';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die('error connecting to the local host'.$conn->connect_error);
    }

    echo "connected successfully";
?>

<br>

<!-- Creating the database -->
<?php
    $sql = "CREATE DATABASE IF NOT EXISTS mbhdbase";
    if($conn->query($sql) === TRUE){
        echo 'mbhdbase database has been successfully created (if it doesnt already exist)';
    } 
    
    else {
    echo 'error creating the database';
    }
?>

<br>

<!-- Creating reports table -->
<?php

    $sql = "CREATE TABLE IF NOT EXISTS reports(
        id INT AUTO_INCREMENT PRIMARY KEY,
        case_num VARCHAR(40),
        court_name VARCHAR(255),
        client VARCHAR(255),
        client_characteristic VARCHAR(50),
        opponent VARCHAR(255),
        opponent_characteristic VARCHAR(50),
        prev_judge TEXT,
        session_decision TEXT,
        notes TEXT,
        session_date DATE,
        timestamp DATETIME
    )";

    if($conn->query($sql) === TRUE){
        echo 'reports table has been succesfully created';
    }

    else{
        echo ' error creating reports table'.$conn->connect_error;
    }

?>

<br>

<!-- Creating cases table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS cases(
        id INT AUTO_INCREMENT PRIMARY KEY,
        case_num VARCHAR(40) NOT NULL,
        court_name VARCHAR(255) NOT NULL,
        client_ar_name VARCHAR(255) NOT NULL,
        client_eng_name VARCHAR(255) NOT NULL,
        client_characteristic VARCHAR(50),
        client_Address VARCHAR(255) NOT NULL,
        client_Telno VARCHAR(13) NOT NULL,
        client_Email VARCHAR(320),
        opponent_ar_name VARCHAR(255),
        opponent_eng_name VARCHAR(255),
        opponent_characteristic VARCHAR(50),
        opponent_Address VARCHAR(255),
        opponent_Telno VARCHAR(13),
        opponent_Email VARCHAR(320),
        prev_judge TEXT,
        session_decision TEXT,
        notes TEXT,
        session_date DATE,
        timestamp DATETIME
    )";

    if($conn->query($sql) === TRUE){
        echo 'Cases table has been succesfully created';
    }

    else{
        echo 'error creating the cases table';
    }
?>

<br>

<!-- Creating file table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS file(
        file_id INT AUTO_INCREMENT PRIMARY KEY,
        file_type VARCHAR(50),
        frelated_place VARCHAR(30),
        file_class VARCHAR(25),
        file_status VARCHAR(50),
        file_subject TEXT,
        file_notes TEXT,
        file_client VARCHAR(255),
        fclient_characteristic VARCHAR(50),
        file_opponent VARCHAR(255),
        fopponent_characteristic VARCHAR(50),
        fpolice_station TEXT,
        fcase_type TEXT,
        file_court TEXT,
        file_prosecution TEXT,
        flegal_researcher VARCHAR(255),
        flegal_advisor VARCHAR(255),
        file_degree VARCHAR(255),
        fcase_num VARCHAR(40),
        file_year INT,
        file_date DATETIME,
        fuploaded_type1 VARCHAR(100),
        file_upload1 BLOB,
        fuploaded_type2 VARCHAR(100),
        file_upload2 BLOB,
        fuploaded_type3 VARCHAR(100),
        file_upload3 BLOB,
        fuploaded_type4 VARCHAR(100),
        file_upload4 BLOB,
        fuploaded_type5 VARCHAR(100),
        file_upload5 BLOB,
        fuploaded_type6 VARCHAR(100),
        file_upload6 BLOB,
        file_timestamp DATETIME
    )";

    if($conn->query($sql) === TRUE){
        echo 'File table has been successfully created';
    } else{
        echo 'error creating file table'.$conn->connect_error;
    }
?>

<br>

<!-- Creating documents table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS case_document(
        did INT AUTO_INCREMENT PRIMARY KEY,
        dfile_no INT,
        dcase_no INT,
        document_date VARCHAR(10),
        document_subject TEXT,
        document_details TEXT,
        document_attachment BLOB,
        document_attachment2 BLOB
    )";

    if($conn->query($sql) === TRUE){
        echo 'Documents table has been successfully created';
    } else{
        echo 'error creating documents table'.$conn->connect_error;
    }
?>

<br>

<!-- Creating session table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS session(
        session_id INT AUTO_INCREMENT PRIMARY KEY,
        session_date VARCHAR(12),
        session_details TEXT,
        session_degree VARCHAR(255),
        session_trial TEXT,
        expert_session INT,
        expert_name VARCHAR(255),
        expert_phone VARCHAR(13),
        expert_amount INT,
        expert_address TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'session table has been successfully created';
    } else{
        echo 'error created a table';
    }
?>

<br>

<!-- Creating job table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS job(
        job_id INT PRIMARY KEY AUTO_INCREMENT,
        job_fid INT,
        job_name TEXT,
        employee_name VARCHAR(255),
        job_degree VARCHAR(255),
        job_details TEXT,
        job_date DATE,
        timestamp DATETIME
    )";

    if($conn->query($sql) === TRUE){
        echo 'job table has been successfully created';
    } else{
        echo 'error creating a table';
    }
?>

<br>

<!-- Creating upload_files table -->
<?php 
    $sql = "CREATE TABLE IF NOT EXISTS upload_files(
        uploaded_id INT AUTO_INCREMENT PRIMARY KEY,
        file_id INT,
        note_id INT,
        new_folder TEXT,
        attachment1 TEXT,
        attachment2 TEXT,
        attachment3 TEXT,
        attachment4 TEXT,
        attachment5 TEXT,
        attachment6 TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'upload_files table has been successfully created';
    } else{
        echo 'error creating upload_files table';
    }
?>

<br>

<!-- Create clients table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS client(
        id INT AUTO_INCREMENT PRIMARY KEY,
        arname VARCHAR(255),
        engname VARCHAR(255),
        client_kind TEXT,
        email VARCHAR(320),
        tel1 VARCHAR(13),
        fax VARCHAR(320),
        tel2 VARCHAR(13),
        notes TEXT,
        address TEXT,
        country TEXT,
        idno VARCHAR(50),
        password VARCHAR(20) ,
        perm INT
    )";

    if($conn->query($sql) === TRUE){
        echo ' client table has been successfully created';
    } else{
        echo 'error creating client table';
    }
?>

<br>

<!-- Creating clients schedule table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS clients_schedule(
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    name VARCHAR(255),
    details TEXT,
    time TEXT,
    date TEXT,
    place TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'clients_schedule table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating clients requests table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS clients_requests(
    id INT AUTO_INCREMENT PRIMARY KEY,
    rc_name VARCHAR(255),
    r_details TEXT,
    rf_no INT,
    r_date TEXT,
    reply TEXT,
    timestamp TEXT
    )";
    
    if($conn->query($sql) === TRUE){
        echo 'clients_requests table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating folder table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS folder(
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'folder table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating client_status table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS client_status(
    id INT AUTO_INCREMENT PRIMARY KEY,
    arname TEXT,
    engname TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'client_status table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating court table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS court(
        id INT AUTO_INCREMENT PRIMARY KEY,
        court_name TEXT,
        timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'court table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating prosecution table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS prosecution(
    id INT AUTO_INCREMENT PRIMARY KEY,
    prosecution_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'prosecution table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating police_station table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS police_station(
    id INT AUTO_INCREMENT PRIMARY KEY,
    policestation_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'police_station table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating case_type table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS case_type(
    id INT AUTO_INCREMENT PRIMARY KEY,
    ct_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'case_type table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating degree table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS degree(
    id INT AUTO_INCREMENT PRIMARY KEY,
    degree_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'degree table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating positions table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS positions(
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'positions table has been succesfully created.';
    }

    else{
        echo 'error creating positions table';
    }
?>

<br>

<!-- Creating job_name table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS job_name(
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_name TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'job_name table has been succesfully created';
    }

    else{
        echo 'error creating job_name table';
    }
?>

<br>

<!-- Creating archive table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS archive(
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT,
    note TEXT,
    degree VARCHAR(255),
    case_no INT,
    year INT,
    client_characteristic VARCHAR(255),
    opponent_characteristic VARCHAR(255),
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'archive table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating user table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    username VARCHAR(40) UNIQUE,
    password VARCHAR(20),
    tel1 VARCHAR(14),
    tel2 VARCHAR(14),
    email VARCHAR(320),
    nationality VARCHAR(255),
    address TEXT,
    passport_no VARCHAR(15),
    passport_exp TEXT,
    residence_date TEXT,
    residence_exp TEXT,
    app_date TEXT,
    job_title TEXT,
    work_place TEXT,
    signin_perm INT,
    photo TEXT,
    passport_residence TEXT,
    practical_qualification TEXT,
    more_files TEXT,
    emp_perms_add INT,
    emp_perms_edit INT,
    emp_perms_delete INT,
    cfiles_rperm INT,
    cfiles_aperm INT,
    cfiles_eperm INT,
    cfiles_dperm INT,
    cfiles_archive_perm INT,
    legalresearcher_aperm INT,
    legaladvisor_aperm INT,
    session_rperm INT,
    session_aperm INT,
    session_eperm INT,
    session_dperm INT,
    degree_aperm INT,
    degree_eperm INT,
    degree_dperm INT,
    note_aperm INT,
    note_eperm INT,
    note_dperm INT,
    attachments_dperm INT,
    admjobs_rperm INT,
    admjobs_aperm INT,
    admjobs_eperm INT,
    admjobs_dperm INT,
    admjobs_pperm INT,
    admjobtypes_rperm INT,
    admjobtypes_aperm INT,
    admjobtypes_eperm INT,
    admjobtypes_dperm INT,
    admprivjobs_rperm INT,
    clients_rperm INT,
    opponents_rperm INT,
    sessionrole_rperm INT,
    accmainterms_rperm INT,
    accmainterms_aperm INT,
    accmainterms_eperm INT,
    accmainterms_dperm INT,
    accsecterms_rperm INT,
    accsecterms_aperm INT,
    accsecterms_eperm INT,
    accsecterms_dperm INT,
    accbankaccs_rperm INT,
    accbankaccs_aperm INT,
    accbankaccs_eperm INT,
    accbankaccs_dperm INT,
    acccasecost_rperm INT,
    acccasecost_aperm INT,
    accrevenues_rperm INT,
    accrevenues_aperm INT,
    accrevenues_dperm INT,
    accexpenses_rperm INT,
    accexpenses_aperm INT,
    accexpenses_dperm INT,
    basic_salary INT,
    travel_tickets INT,
    oil_cost INT,
    housing_cost INT,
    living_cost INT,
    logins_num INT,
    lastlogin timestamp
    )";

    if($conn->query($sql) === TRUE){
        echo 'user table has been succesfully created.';
    }

    else{
        echo 'error creating user table';
    }
?>

<br>

<!-- Creating vacations table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS vacations(
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    type TEXT,
    starting_date TEXT,
    ending_date TEXT,
    timestamp TEXT
    )";

if($conn->query($sql) === TRUE){
    echo 'vacations table has been succesfully created.';
}

else{
    echo 'error creating vacations table';
}
?>

<br>

<!-- Creating warnings table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS warnings(
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    warning_reason TEXT,
    warning_date TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'warnings table has been succesfully created.';
    }

    else{
        echo 'error creating warnings table';
    }
?>

<br>

<!-- Creating vehicles table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS vehicles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    type TEXT,
    num INT,
    lic_expir TEXT,
    insur_expir TEXT,
    branch TEXT,
    photo TEXT,
    notes TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'vehicles table has been succesfully created.';
    }

    else{
        echo 'error creating vehicles table';
    }
?>

<br>

<!-- Creating contracts table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS contracts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    rent_lic TEXT,
    owner TEXT,
    place TEXT,
    starting_d TEXT,
    ending_d TEXT,
    branch TEXT,
    cont_lic_pic TEXT,
    attachment1 TEXT,
    attachment2 TEXT,
    attachment3 TEXT,
    attachment4 TEXT,
    notes TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'contracts table has been succesfully created.';
    }

    else{
        echo 'error creating contracts table';
    }
?>

<br>

<!-- Creating lawyers table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS lawyers(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    tel_no VARCHAR(14),
    about TEXT,
    attachment1 TEXT,
    attachment2 TEXT,
    attachment3 TEXT,
    attachment4 TEXT,
    attachment5 TEXT,
    attachment6 TEXT,
    timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'lawyers table has been succesfully created.';
    }

    else{
        echo 'error creating lawyers table';
    }
?>

<br>

<!-- Creating tasks table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS tasks(
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT,
        case_no INT,
        file_no INT,
        client_id INT,
        task_type TEXT,
        priority TEXT,
        duedate TEXT,
        details TEXT,
        timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'tasks Table has been succesfully created';
    }

    else{
        echo 'error creating tasks table';
    }
?>

<br>

<!-- Creating file_note table -->
<?php
    $sql = "CREATE TABLE IF NOT EXISTS file_note(
        id INT AUTO_INCREMENT PRIMARY KEY,
        file_id INT,
        note TEXT,
        timestamp TEXT
    )";

    if($conn->query($sql) === TRUE){
        echo 'file_note Table has been succesfully created';
    }

    else{
        echo 'error creating file_note table';
    }
?>

<br>

<!-- Creating logs table -->
<?php

    $sql = "CREATE TABLE IF NOT EXISTS logs(
        username VARCHAR(255),
        action TEXT,
        timestamp DATETIME)";

    if($conn->query($sql) === TRUE){
        echo 'logs table has been successfully created';
    } 
    
    else{
        echo 'error creating logs table';
    }
?>