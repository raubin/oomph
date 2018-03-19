<?php

// Define url
$url = 'https://api-v3.mbta.com/routes';

//  Initiate curl
$ch = curl_init();

// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set the url
curl_setopt($ch, CURLOPT_URL,$url);

// Execute
$results=curl_exec($ch);

// Closing
curl_close($ch);

// Decode result
$results = json_decode($results, true);

// Define trains array
$trains = array(
  'Rapid Transit' => array(),
  'Commuter Rail' => array(),
  'Limited Service' => array(),
);

// Iterate through each result's data
foreach($results['data'] as $result) {

  // Determine the type of train
  switch($result['attributes']['description']) {

      case 'Rapid Transit':

          // Get name
          $name = strlen($result['attributes']['short_name']) > 1 ? $result['attributes']['short_name'] : $result['attributes']['long_name'];

          // Add result to trains
          array_push($trains['Rapid Transit'], array(
            'name' => $name,
            'color' => 'white',
            'background color' => $result['attributes']['color']
          ));

          break;

        case 'Commuter Rail':

            // Add result to trains
            array_push($trains['Commuter Rail'], array(
              'color' => 'white',
              'name' => $result['attributes']['long_name'],
              'background color' => $result['attributes']['color']
            ));

            break;

          case 'Limited Service':

            // Get name
            $name = $result['attributes']['long_name'] == '' ? $result['attributes']['short_name'] : $result['attributes']['long_name'];

            // Get color
            $color = $result['attributes']['long_name'] == '' ? 'black' : 'white';


            // Add result to trains
            array_push($trains['Limited Service'], array(
              'name' => $name,
              'color' => $color,
              'background color' => $result['attributes']['color']
            ));

            break;
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>MBTA Lines</title>
  </head>
  <style>

    #trains {
      width: 400px;
    }

    .title {
      text-align: center;
      font-size: 25px;
      padding: 10px;
      font-weight: 500;
    }

    table {
      width: 100%;
    }

    table td {
      font-size: 20px;
      padding: 5px;
    }

  </style>
  <body>
    <div id="trains">
      <?php foreach($trains as $line_name => $lines) { ?>
        <div class="title">
          <?= $line_name ?>
        </div>
        <table>
          <?php foreach($lines as $line) { ?>
          <tr>
            <td style="color: <?= $line['color'] ?>; background-color: #<?= $line['background color'] ?>;">
              <?= $line['name'] ?>
            </td>
          </tr>
        <?php } ?>
        </table>
      <?php } ?>
    </div>
  </body>
</html>
