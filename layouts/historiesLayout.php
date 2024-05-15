<!DOCTYPE html><html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Show Histories</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="./css/style.css">
    
  
</head>
    <body>

        <form method="GET">
            <input type="hidden" value="showHistories" name="action">
            
            <?php $countries = getCountries(); ?>
            <label for="country">Country:</label>
            <select name="country">
                <option value="all" <?php echo $country == 'all' ? 'selected' : '' ?>>All</option>
                <?php foreach ($countries as $item) { ?>
                    <option value="<?php echo $item;?>" <?php echo $country == $item ? 'selected' : '' ?>><?php echo $item;?></option>
                <?php } ?>
            </select>

            <label for="dateFrom">Date From:</label>
            <input type="text" class="datepicker" name="dateFrom" value="<?php echo date('Y-m-d', strtotime($datetimeFrom));?>">

            <label for="dateTo">Date To:</label>
            <input type="text" class="datepicker" name="dateTo" value="<?php echo date('Y-m-d', strtotime($datetimeTo));?>">

            <button type="submit" name="submitForm">Submit</button>
        </form>

        <?php $results = getHistories($country, $datetimeFrom, $datetimeTo); ?>
        <table border='1'>
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Total Active User's Amount</th>
                    <th>Last History Date Time</th>
                    <th>No. Of Unique Users</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $item) { ?>
                    <tr>
                        <td><a target="_blank" href='index.php?action=showHistoriesByCountryAndDate&country=<?php echo $item['country'];?>&dateFrom=<?php echo date('Y-m-d', strtotime($datetimeFrom));?>&dateTo=<?php echo date('Y-m-d', strtotime($datetimeTo));?>'><?php echo $item['country'];?></a></td>
                        <td><?php echo $item['total_active_user_amount'];?></td>
                        <td><?php echo $item['last_history_datetime'];?></td>
                        <td><?php echo $item['number_of_unique_users'];?></td>
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
