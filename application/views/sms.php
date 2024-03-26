<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>JSON to DataTable</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
</head>
<body>
<table id="example" class="display" style="width:100%">
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var jsonData = [
        {"name": "John Doe", "age": 30, "city": "New York"},
        {"name": "Jane Smith", "age": 25, "city": "San Francisco"},
        {"name": "Bob Johnson", "age": 35, "city": "Chicago"}
    ];

    $('#example').DataTable({
        data: jsonData,
        columns: [
            { data: 'name', title: 'Name' },
            { data: 'age', title: 'Age' },
            { data: 'city', title: 'City' }
        ]
    });
});
</script>
</body>
</html>