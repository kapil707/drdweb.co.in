<?php
$array1 = array(
    "status" => 2,
    "amount" => 3531,
    "date" => "2024-05-14",
    "time" => "2024-05-14 18:46:39",
    "upi_no" => "413562311442",
    "orderid" => "T2405141846323686882047",
    "type" => "SMS",
    "_id" => 20169,
    "find_by" => "Chemist Table4",
    "find_chemist_id" => "",
    "received_from" => "zasim.akhtar@ybl",
    "process_value" => "",
    "process_name" => "zasim.akhtar",
    "find_invoice_chemist_id" => "G37:-SB-24-85865 Amt.3531,G281:-SB-24-91110 Amt.3531,S683:-SB-24-91077 Amt.3531",
    "done_chemist_id" => "",
    "done_status" => 0,
    "whatsapp_id" => 187,
    "whatsapp_body" => "S683",
    "whatsapp_image" => "/v1/chat/646f5b15ab5ee824ea9bdd5f/files/664364c3707ec5000a97cc78/download",
    "whatsapp_body2" => "D. for **TAX INVOICE Billed To & Shipped To SAAD PHARMACY (S683) ..."
);

$array2 = array(
    "status" => 1,
    "amount" => 3531.00,
    "date" => "2024-05-14",
    "time" => "",
    "upi_no" => "413562311442",
    "orderid" => "413562311442",
    "type" => "Statement",
    "_id" => 1,
    "find_by" => "Chemist Table4",
    "find_chemist_id" => "",
    "received_from" => "ZASIM.AKHTAR@YBL",
    "process_value" => "",
    "process_name" => "ZASIM.AKHTAR",
    "find_invoice_chemist_id" => "G37:-SB-24-85865 Amt.3531,G281:-SB-24-91110 Amt.3531,S683:-SB-24-91077 Amt.3531",
    "done_chemist_id" => "",
    "done_status" => 0,
    "whatsapp_id" => 0,
    "whatsapp_body" => "",
    "whatsapp_image" => "",
    "whatsapp_body2" => ""
);

$combined_array = array();

if ($array1['upi_no'] == $array2['upi_no']) {
    $combined_array = array(
        "upi_no" => $array1['upi_no'],
        "sms" => $array1,
        "statement" => $array2
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Combined Data</title>
</head>
<body>
    <table border="1">
        <tr>
            <th>UPI No</th>
            <th>Status (SMS)</th>
            <th>Amount (SMS)</th>
            <th>Date (SMS)</th>
            <th>Time (SMS)</th>
            <th>Order ID (SMS)</th>
            <th>Received From (SMS)</th>
            <th>Process Name (SMS)</th>
            <th>Find Invoice Chemist ID (SMS)</th>
            <th>WhatsApp Body (SMS)</th>
            <th>Status (Statement)</th>
            <th>Amount (Statement)</th>
            <th>Date (Statement)</th>
            <th>Time (Statement)</th>
            <th>Order ID (Statement)</th>
            <th>Received From (Statement)</th>
            <th>Process Name (Statement)</th>
            <th>Find Invoice Chemist ID (Statement)</th>
            <th>WhatsApp Body (Statement)</th>
        </tr>
        <tr>
            <td><?php echo $combined_array['upi_no']; ?></td>
            <td><?php echo $combined_array['sms']['status']; ?></td>
            <td><?php echo $combined_array['sms']['amount']; ?></td>
            <td><?php echo $combined_array['sms']['date']; ?></td>
            <td><?php echo $combined_array['sms']['time']; ?></td>
            <td><?php echo $combined_array['sms']['orderid']; ?></td>
            <td><?php echo $combined_array['sms']['received_from']; ?></td>
            <td><?php echo $combined_array['sms']['process_name']; ?></td>
            <td><?php echo $combined_array['sms']['find_invoice_chemist_id']; ?></td>
            <td><?php echo $combined_array['sms']['whatsapp_body']; ?></td>
            <td><?php echo $combined_array['statement']['status']; ?></td>
            <td><?php echo $combined_array['statement']['amount']; ?></td>
            <td><?php echo $combined_array['statement']['date']; ?></td>
            <td><?php echo $combined_array['statement']['time']; ?></td>
            <td><?php echo $combined_array['statement']['orderid']; ?></td>
            <td><?php echo $combined_array['statement']['received_from']; ?></td>
            <td><?php echo $combined_array['statement']['process_name']; ?></td>
            <td><?php echo $combined_array['statement']['find_invoice_chemist_id']; ?></td>
            <td><?php echo $combined_array['statement']['whatsapp_body']; ?></td>
        </tr>
    </table>
</body>
</html>
