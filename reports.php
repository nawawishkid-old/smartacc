<!DOCTYPE html>
<html>
<head>
  <title>Reports | SMARTACC</title>
  <?php
    $con = mysqli_connect("localhost", "root", "", "smartacc")
        or die ("Error: connection failed, " . mysqli_connect_error());
    mysqli_query($con, "SET character_set_results=utf8")
        or die ("Error: Cannot set character set result.");
    mysqli_query($con, "SET character_set_client=utf8")  
        or die ("Error: Cannot set character set client.");
    mysqli_query($con, "SET character_set_connection=utf8")
        or die ("Error: Cannot set character set connection.");
    
    // Set default date to SQL Wildcard
    $year = $month = $day = "%";

    // Retrieved date value
    $transaction_type = "EXPENSE";
    $year = "2016";

    function fetch($query) {
      global $con;
      $result = mysqli_query($con, $query) or die ("Error: could not send query, " . mysqli_error($con));
      $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
      return $rows;
    }

    // Fetch total amount based on category
    function totalAmount_category($transaction_type) {
      if($transaction_type !== "INCOME"
          && $transaction_type !== "EXPENSE"
        ) {return false;}
      $tr_type = ($transaction_type === "INCOME" ? "in" : "ex");
      global $year, $month, $day;
      $query = "SELECT DISTINCT {$tr_type}_categories.{$tr_type}_cats AS category, 
                  (
                    SELECT SUM(record.amount)
                    FROM record
                    WHERE transaction_type = '{$tr_type}'
                    AND categories = {$tr_type}_categories.{$tr_type}_cats
                    AND date LIKE '{$year}-{$month}-{$day}'
                  ) AS total_amount
                FROM {$tr_type}_categories
                INNER JOIN record
                ON {$tr_type}_categories.{$tr_type}_cats = record.categories
                ORDER BY total_amount DESC;";
      return fetch($query);
    }
    //print_r($result);
  ?>
</head>
<body>
  <p>Date: <?php echo "{$year}-{$month}-{$day}";?></p>
  <table>
    <thead>
      <td>EXPENSE<td>
    </thead>
    <tbody>
      <tr>
        <td>Category</td>
        <td>Total amount</td>
      </tr>
      <?php
        $result = totalAmount_category("EXPENSE");
        $total_amount = 0;
        for($i = 0; $i < count($result); $i++) {
          $cat = ucfirst($result[$i]['category']);
          $amount = number_format($result[$i]['total_amount'], 2);
          $total_amount += $result[$i]['total_amount'];
          echo "<tr><td>{$cat}</td><td>{$amount}</td></tr>";
        }
      ?>
      <tr>
        <td>Total</td>
        <td><?php echo number_format($total_amount, 2);?></td>
      </tr>
    </tbody>
  </table>
  <hr>
  <table>
    <thead>
      <td>INCOME<td>
    </thead>
    <tbody>
      <tr>
        <td>Category</td>
        <td>Total amount</td>
      </tr>
      <?php
        $result = totalAmount_category("INCOME");
        $total_amount = 0;
        for($i = 0; $i < count($result); $i++) {
          $cat = ucfirst($result[$i]['category']);
          $amount = number_format($result[$i]['total_amount'], 2);
          $total_amount += $result[$i]['total_amount'];
          echo "<tr><td>{$cat}</td><td>{$amount}</td></tr>";
        }
      ?>
      <tr>
        <td>Total</td>
        <td><?php echo number_format($total_amount, 2);?></td>
      </tr>
    </tbody>
  </table>
  <?php mysqli_close($con) ?>
</body>
</html>