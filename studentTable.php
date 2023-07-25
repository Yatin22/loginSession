<!DOCTYPE html>
<head>
    <title>Student Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style2.css">
    
</head>
<body>
<?php
include "config.php";

function fetchTableRows($conn, $offset, $records_per_page, $filters)
{
    $query = "SELECT * FROM student";

    $bindParams = array();
    $whereClause = array();

    if (!empty($filters)) {
        foreach ($filters as $field => $value) {
            // Ensure valid field names to prevent SQL injection
            $validFields = array('student_name', 'phone', 'email', 'class', 'gender', 'date_of_birth', 'status');
            if (in_array($field, $validFields)) {
                $whereClause[] = "$field LIKE ?";
                $bindParams[] = "%" . $value . "%";
            }
        }
    }

    if (!empty($whereClause)) {
        $query .= " WHERE " . implode(" AND ", $whereClause);
    }

    $query .= " LIMIT ?, ?";
    $bindParams[] = $offset;
    $bindParams[] = $records_per_page;

    $stmt = $conn->prepare($query);

    if (!empty($bindParams)) {
        $types = str_repeat('s', count($bindParams));
        $stmt->bind_param($types, ...$bindParams);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $tableRows = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $updateTime = $row['updated_time'];
        $status = $row['status'];
        $update = true;
        if ($status == 1) {
            $status = "Active";
            $update = true;
        } else {
            $status = "Inactive";
            $update = false;
        }
        if ($updateTime == null) {
            $updateTime = "Not Updated Yet";
        }
        $id = $row['student_id'];
        $tableRows .= "<tr>";
        $tableRows .= "<td>" . $row['student_id'] . "</td>";
        $tableRows .= "<td>" . $row['student_name'] . "</td>";
        $tableRows .= "<td>" . $row['father_name'] . "</td>";
        $tableRows .= "<td>" . $row['phone'] . "</td>";
        $tableRows .= "<td>" . $row['email'] . "</td>";
        $tableRows .= "<td>" . $row['class'] . "</td>";
        $tableRows .= "<td>" . $row['gender'] . "</td>";
        $tableRows .= "<td>" . $row['note'] . "</td>";
        $tableRows .= "<td>" . $row['date_of_birth'] . "</td>";
        $tableRows .= "<td>" . $row['created_time'] . "</td>";
        $tableRows .= "<td>" . $updateTime . "</td>";
        $tableRows .= "<td>" . $status . "</td>";
        $tableRows .= "<td>" . $row['created_by'] . "</td>";
        $tableRows .= "<td style='display: flex !important;, border-radius:30px; min-height:90px;align-items:center'>";
        if ($update) {
            $tableRows .= " <a href='studentUpdate.php?id=$id'><button class='btn btn-primary'>Update</button></a>";
            $tableRows .= "<a><button class='btn btn-danger' onClick='HandleDelete($id)'>Delete</button></a>";
        } else {
            $tableRows .= "You can't update this";
        }

        $tableRows .= "</td>";
        $tableRows .= "</tr>";
    }

    mysqli_free_result($result);

    return $tableRows;
}

$records_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$filters = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!empty($_POST['filter-name'])) {
        $filters['student_name'] = $_POST['filter-name'];
    }

    if (!empty($_POST['filter-phone'])) {
        $filters['phone'] = $_POST['filter-phone'];
    }

    if (!empty($_POST['filter-email'])) {
        $filters['email'] = $_POST['filter-email'];
    }

    if (!empty($_POST['filter-class'])) {
        $filters['class'] = $_POST['filter-class'];
    }

    if (!empty($_POST['filter-gender'])) {
        $filters['gender'] = $_POST['filter-gender'];
    }

    if (!empty($_POST['filter-dob'])) {
        $filters['date_of_birth'] = $_POST['filter-dob'];
    }

    // Handle status filter (Active, Inactive, or All)
    if (!empty($_POST['filter-status'])) {
        $status_filter = $_POST['filter-status'];
        if ($status_filter == "Active") {
            $filters['status'] = 1;
        } elseif ($status_filter == "Inactive") {
            $filters['status'] = 0;
        }
    }
}

$tableRows = fetchTableRows($conn, $offset, $records_per_page, $filters);
?>

<a href="userPage.php">
    <button class="btn-my-profile">My Profile</button>
</a>
<h1>Student Information</h1>
<form method="post" onsubmit="return validateForm()">

    <div class="filters">
        <label>Student Name:</label>
        <input type="text" id="filter-name" name="filter-name" value="<?php echo isset($_POST['filter-name']) ? $_POST['filter-name'] : ''; ?>">

        <label>Phone:</label>
        <input type="text" id="filter-phone"  name="filter-phone" value="<?php echo isset($_POST['filter-phone']) ? $_POST['filter-phone'] : ''; ?>">

        <label>Email:</label>
        <input type="text" id="filter-email" name="filter-email" value="<?php echo isset($_POST['filter-email']) ? $_POST['filter-email'] : ''; ?>">

        <label>Class:</label>
        <select id="filter-class" name="filter-class" style="width: 220px;">
    <option value="">All</option>
    <?php
    // Loop to generate options from 1 to 12
    for ($i = 1; $i <= 12; $i++) {
        $selected = isset($_POST['filter-class']) && $_POST['filter-class'] == $i ? 'selected' : '';
        echo "<option value='$i' $selected>$i</option>";
    }
    ?>
</select>
<br>
        <label>Gender:</label>
        <input type="text" id="filter-gender" name="filter-gender" value="<?php echo isset($_POST['filter-gender']) ? $_POST['filter-gender'] : ''; ?>">

        <label>Date of Birth:</label>
        <input type="date" id="filter-dob" name="filter-dob" value="<?php echo isset($_POST['filter-dob']) ? $_POST['filter-dob'] : ''; ?>">

        <label>Status:</label>
        <select id="filter-status" name="filter-status">
            <option value="">All</option>
            <option value="Active" <?php echo (isset($_POST['filter-status']) && $_POST['filter-status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
            <option value="Inactive" <?php echo (isset($_POST['filter-status']) && $_POST['filter-status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
        </select>

        <button id="btn-filter" type="submit">Apply Filters</button>
        <button id="btn-reset" type="button" onClick="ResetFilters()">Reset Filters</button>
    </div>

</form>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Father Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Class</th>
            <th>Gender</th>
            <th>Note</th>
            <th>Date of Birth</th>
            <th>Created Datetime</th>
            <th>Updated Datetime</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php echo $tableRows; ?>
        </tbody>
    </table>
</div>

<div class="add-button-container">
    <a href="studentInsert.php">
        <button class="btn-add">Add New Student</button>
    </a>
</div>

<div class="pagination justify-content-center">
    <ul class="pagination pagination-sm"></ul>
    <?php
    // Pagination links
    $query = "SELECT COUNT(*) AS total FROM student";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_records = $row['total'];
    $total_pages = ceil($total_records / $records_per_page);

    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li class='page-item " . ($i == $current_page ? "active" : "") . "'>";
        echo "<a class='page-link' href='?page=$i'>$i</a>";
        echo "</li>";
    }
    ?>
</ul>
</div>

<script>
    function ResetFilters() {
        document.getElementById("filter-name").value = "";
        document.getElementById("filter-phone").value = "";
        document.getElementById("filter-email").value = "";
        document.getElementById("filter-class").value = "";
        document.getElementById("filter-gender").value = "";
        document.getElementById("filter-dob").value = "";
        document.getElementById("filter-status").value = "";
        document.getElementById("btn-filter").click();
    }
    function HandleDelete(id){
        var response = confirm("You really want to Delete");
        if (response) {
            window.location.replace(`studentDelete.php?id=${id}`);
        } else {
            window.location.reload();
        }
    }
    function validateForm() {
        var name = document.getElementById("filter-name").value.trim();
        var phone = document.getElementById("filter-phone").value.trim();
        var email = document.getElementById("filter-email").value.trim();
        var dob = document.getElementById("filter-dob").value.trim();

        // Validate name field (should not be empty)
        if (name !== "" && name.length < 3) {
            alert("Student Name should be at least 3 characters long.");
            return false;
        }

        // Validate phone field (should be empty or numeric)
        if (phone !== "" && isNaN(phone)) {
            alert("Phone Number should be numeric.");
            return false;
        }
        if(phone !== "" && phone.toString().length != 10){
            alert("Enter a valid phone No.");
            return false;
        }

        if (email !== "" && !isValidEmail(email)) {
            alert("Please enter a valid Email Address format.");
            return false;
        }

        if (dob !== "" && !isValidDate(dob)) {
            alert("Please enter a valid Date of Birth format (YYYY-MM-DD).");
            return false;
        }
        

        // Form submission will proceed if all validations pass
        return true;
    }

    // Helper function to validate email format using a regular expression
    function isValidEmail(email) {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    // Helper function to validate date format using a regular expression
    function isValidDate(date) {
        var datePattern = /^\d{4}-\d{2}-\d{2}$/;
        return datePattern.test(date);
    }
</script>

</body>
</html>
