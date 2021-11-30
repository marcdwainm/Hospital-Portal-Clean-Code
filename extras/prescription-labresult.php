<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='../css/prescription.css'>
    <title></title>
</head>

<body>
    <?php
    include '../php_processes/db_conn.php';

    $userid = $_GET['pid'];
    $patient_name = '';
    $patient_age = '';
    $date_today = '';
    $sex = '';

    $query = "SELECT * FROM user_table WHERE patient_id = $userid";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
        $contact = $row['contact_num'];
        $sex = ucfirst($row['sex']);

        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;

        $patient_name = $fullname;
        $patient_age = $age;
        $date_today = $to->format('m/d/Y');
    }

    if ($_GET['pid'] == '0000') {
        $patient_name = ucwords($_GET['pname']);
        $patient_age = 'Unspecified';
        $to = new DateTime('today');
        $date_today = $to->format('m/d/Y');
        $sex = 'Unspecified';
    }

    ?>

    <?php
    // IF DOCUMENT TYPE IS PRESCRIPTION
    if ($_GET['docType'] == 'prescription') {
        echo "
            <iframe name='dummyframe' id='dummyframe' style='display: none;'></iframe>
            <div class='buttons'>
                <div class='buttons-edit'>
                    <button id='add-div'><i class='far fa-plus-square'></i> Add Drug</button>
                    <button id='revert'><i class='fas fa-trash-alt'></i> Revert</button>
                </div>

                <div class='buttons-download'>
                    <button id='download' class='btns'>Upload</button>
                    <button id='copy' class='btns'>Download a Copy</button>
                    <button id='print' class='btns'>Print</button>
                </div>
            </div>
            <div class='container'>
                <div class='prescription-container' id='invoice'>
                    <h2>TWIN CARE MEDICAL CLINIC AND DIAGNOSTIC LABORATORY</h2>
                    <h4>02 Tapatan Road<br />Sulucan 3012<br />Angat, Bulacan</h4>
                    <div class='patient-info'>
                        <div class='name-age'>
                            <div class='name-container'>
                                <span>Patient Name: </span>
                                <span contenteditable='true' class='underlined' id='name'>$patient_name</span>
                            </div>
                            <div class='age-container'>
                                <span>Age: </span>
                                <span contenteditable='true' class='underlined' id='age'>$patient_age</span>
                            </div>
                        </div>
                        <div class='address-date'>
                            <div class='address-container'>
                                <span>Address: </span>
                                <span contenteditable='true' class='underlined' id='address'>Patient Address</span>
                            </div>
                            <div class='date-container'>
                                <span>Date: </span>
                                <span contenteditable='true' class='underlined' id='date'>$date_today</span>
                            </div>
                        </div>
                    </div>

                    <div class='medicine'>
                        <img src='../img/rx.png'>
                        <div class='medicine-table' id='medicine-table'>
                            <div class='medicine-table-header'>
                                <span class='left'>Drug</span>
                                <span class='center'>Amount</span>
                                <span class='right'>Frequency</span>
                            </div>

                            <div class='medicine-table-content' id='prescription-item'>
                                <span contenteditable='true' class='left'>Drug Name</span>
                                <span contenteditable='true' class='center'>0mg</span>
                                <span contenteditable='true' class='right'>Sample Frequency</span>
                                <button class = 'delete-div'>Delete</button>
                            </div>
                        </div>
                    </div>

                    <div class='footer'>
                        <span>Signature: Doctor's Signature Here</span>
                    </div>
                </div>
            </div>
            ";
        // ELSE IF DOCUMENT IS A LABRESULT
    } else if ($_GET['docType'] == 'labresult') {
        $fullname_doctor = $_GET['doctorname'];
        echo "
            <iframe name='dummyframe' id='dummyframe' style='display: none;'></iframe>
            <div class='buttons'>
                <div class='buttons-edit'>
                    <button id='add-head-lab' class = 'add-head-lab'><i class='far fa-plus-square'></i> Add Head</button>
                    <button id='add-div-lab' class = 'add-div-lab'><i class='far fa-plus-square'></i> Add Test</button>
                    <button id='revert-lab'><i class='fas fa-trash-alt'></i> Revert</button>
                </div>

                <div class='buttons-download'>
                    <button id='download' class='btns'>Upload</button>
                    <button id='copy' class='btns'>Download a Copy</button>
                    <button id='print' class='btns'>Print</button>
                </div>
            </div>


            <div class='container'>
                <div class='prescription-container' id='invoice'>
                    <h2>TWIN CARE MEDICAL CLINIC AND DIAGNOSTIC LABORATORY</h2>
                    <h4>02 Tapatan Road, Sulucan 3012, Angat, Bulacan</h4>
                    <div class='patient-info'>
                        <div class='name-age'>
                            <div class='name-container'>
                                <span>Patient Name: </span>
                                <span contenteditable='true' class='underlined' id='name'>$patient_name</span>
                            </div>
                            <div class='age-container'>
                                <span>Age: </span>
                                <span contenteditable='true' class='underlined' id='age'>$patient_age</span>
                            </div>
                        </div>
                        <div class='address-date'>
                            <div class='address-container'>
                                <span>Sex: </span>
                                <span contenteditable='true' class='underlined' id='sex'>$sex</span>
                            </div>
                            <div class='date-container'>
                                <span>Date: </span>
                                <span contenteditable='true' class='underlined' id='date'>$date_today</span>
                            </div>
                        </div>
                        <div class='doctor-container'>
                            <span>Doctor: </span>
                            <span contenteditable='true' class='underlined' id='doctor'>$fullname_doctor</span>
                        </div>
                    </div>

                    <div class='medicine lab-result'>
                        <h2>Test Results</h2>
                        <div class='medicine-table' id='medicine-table'>
                            <div class='medicine-table-header header-lab'>
                                <span>Test Name</span>
                                <span>Result</span>
                                <span>Normal Range</span>
                                <span>Units</span>
                            </div>

                            <div class='lab-result-content' id='prescription-item'>
                                <span contenteditable='true'>Test Name</span>
                                <span contenteditable='true'>0</span>
                                <span contenteditable='true'>#/#</span>
                                <span contenteditable='true'>0/0</span>
                                <button class = 'delete-div'>Delete</button>
                            </div>
                        </div>
                    </div>

                    <div class='footer'>
                        <span>Signature: Doctor's Signature Here</span>
                    </div>
                </div>
            </div>
            ";
    }

    ?>
</body>
<script src='../js/pdf-generator.js'></script>
<script src='../js/base64-to-pdf.js'></script>

</html>