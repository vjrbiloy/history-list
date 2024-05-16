<!DOCTYPE html><html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Show Histories By Country and Date</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="./css/style.css">

  
</head>
    <body>

        <form method="GET">
            <input type="hidden" value="showHistoriesByCountryAndDate" name="action">
            <input type="hidden" value="country" name="<?php echo $country;?>">
            
            <label for="country">Username:</label>
            <input type="text" name="username" value="<?php echo str_replace('%', '', $username);?>">

            <label for="dateFrom">Date From:</label>
            <input type="text" class="datepicker" name="dateFrom" value="<?php echo date('Y-m-d', strtotime($datetimeFrom));?>">

            <label for="dateTo">Date To:</label>
            <input type="text" class="datepicker" name="dateTo" value="<?php echo date('Y-m-d', strtotime($datetimeTo));?>">

            <button type="submit" name="submitForm">Submit</button>
        </form>

        <?php $results = getHistoriesByCountryAndDate($country, $username, $datetimeFrom, $datetimeTo); ?>
        <table border='1'>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Total User's Amount</th>
                    <th>Last History Date Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $item) { ?>
                    <tr>
                        <td><?php echo $item['user_id'];?></td>
                        <td><?php echo $item['username'];?></td>
                        <td><?php echo $item['total_user_amount'];?></td>
                        <td><?php echo $item['last_history_datetime'];?></td>
                    </tr>
                <?php } ?>
            </tbody>
            
        </table>

        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>

        <script>
            $( function() {
                $( ".datepicker" ).datepicker({
                    dateFormat: "yy-mm-dd", // Custom date format
                    showAnim: "slideDown" // Animation effect
                });
            } );
        </script>

    </body>
</html>




