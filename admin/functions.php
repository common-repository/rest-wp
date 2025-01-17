<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rest_wp_page_content() {
    echo '<h1>REST WP</h1>';
    echo '<br>';
    echo '<p>Send an API request to an endpoint, retrieve data from a data source, or test an API\'s functionality.</p>';
    if (isset($_POST['endpoint'])) {
    $endpoint = sanitize_text_field($_POST['endpoint']);
    $endpoint = esc_url_raw($endpoint);
    if (!wp_http_validate_url($endpoint)) {
    // Display an error message if the endpoint URL is not valid
    ?>
    <div class="error-message">Please enter a valid endpoint URL.</div>
    <?php
    }}
    ?>
    <div class="form-container">
    <form method="post" action="">
       <label for="endpoint" style="font-size: 15px; font-weight: bold;">Endpoint URL:</label><br><br>
       <input type="text" id="endpoint" name="endpoint" value="<?php if (isset($_POST['endpoint'])) { echo esc_attr( $_POST['endpoint'] ); } ?>"></br></br>
       <label for="method" style="font-size: 15px; font-weight: bold;">Request Method:</label><br><br>
       <select id="method" name="method">
           <option value="GET" <?php if (isset($_POST['method']) && $_POST['method'] == 'GET') { echo 'selected'; } ?>>GET</option>
           <option value="POST" <?php if (isset($_POST['method']) && $_POST['method'] == 'POST') { echo 'selected'; } ?>>POST</option>
           <option value="PUT" <?php if (isset($_POST['method']) && $_POST['method'] == 'PUT') { echo 'selected'; } ?>>PUT</option>
           <option value="DELETE" <?php if (isset($_POST['method']) && $_POST['method'] == 'DELETE') { echo 'selected'; } ?>>DELETE</option>
       </select></br></br>
           <label for="headers" style="font-size: 15px; font-weight: bold;">Headers (optional):</label>
           <button type="button" id="add-another" onclick="addKeyValueField()">Add another</button><br><br>
           <div id="key-value-fields">
               <label for="key1">Key:</label>
               <input type="text" id="key1" name="key1" value="<?php if (isset($_POST['key1'])) { echo esc_attr( $_POST['key1'] ); } ?>">
               <label for="value1">Value:</label>
               <input type="text" id="value1" name="value1" value="<?php if (isset($_POST['value1'])) { echo esc_attr( $_POST['value1'] ); } ?>">
               
               <?php
               $n = 2;
               while(isset($_POST['key'.$n])){
                 ?>

                 <div>
                   <label for="<?php echo esc_attr( 'key'.$n ); ?>">Key:</label>
                   <input type="text" id="<?php echo esc_attr('key'.$n); ?>" name="<?php echo esc_attr('key'.$n); ?>" value="<?php echo esc_attr($_POST['key'.$n]); ?>">
                   <label for="<?php echo esc_attr( 'value'.$n ); ?>">Value:</label>
                   <input type="text" id="<?php echo esc_attr('value'.$n); ?>" name="<?php echo esc_attr('value'.$n); ?>" value="<?php echo esc_attr($_POST['value'.$n]); ?>">
                   <button type="button" onclick="deleteKeyValueField(this)">Delete</button>
                 </div>
                 
                 <?php
                 $n++;
               }
               ?>
           </div>
           </br>
           <label for="body" style="font-size: 15px; font-weight: bold;">Body (optional):</label>
           <button type="button" id="add-another-body" onclick="addKeyValueFieldBody()">Add another</button><br><br>
          <div id="key-value-fields-body">
              <label for="key1_body">Key:</label>
              <input type="text" id="<?php echo esc_attr('key1_body'); ?>" name="<?php echo esc_attr('key1_body'); ?>" value="<?php if (isset($_POST['key1_body'])) { echo esc_attr($_POST['key1_body']); } ?>">
              <label for="value1_body">Value:</label>
              <input type="text" id="<?php echo esc_attr('value1_body'); ?>" name="<?php echo esc_attr('value1_body'); ?>" value="<?php if (isset($_POST['value1_body'])) { echo esc_attr($_POST['value1_body']); } ?>">
              
              <?php
              $m = 2;
              while(isset($_POST['key'.$m.'_body'])){
                ?>

            <div>
              <label for="<?php echo 'key'.$m.'_body'; ?>">Key:</label>
              <input type="text" id="<?php echo esc_attr( 'key'.$m.'_body' ); ?>" name="<?php echo esc_attr( 'key'.$m.'_body' ); ?>" value="<?php echo esc_attr( $_POST['key'.$m.'_body'] ); ?>">
              <label for="<?php echo 'value'.$m.'_body'; ?>">Value:</label>
              <input type="text" id="<?php echo esc_attr( 'value'.$m.'_body' ); ?>" name="<?php echo esc_attr( 'value'.$m.'_body' ); ?>" value="<?php echo esc_attr( $_POST['value'.$m.'_body'] ); ?>">
              <button type="button" onclick="deleteKeyValueFieldBody(this)">Delete</button>
              </div>
              <?php
              $m++;
                }
                ?>
             </div></br>
             
           <input type="submit" value="Send Request">
        </form>

       <script>
          var fieldIndex = <?php echo esc_js($n); ?>;
          function addKeyValueField() {
              var keyValueFields = document.getElementById('key-value-fields');
              var newFields = document.createElement('div');
              newFields.innerHTML = '<label for="key' + fieldIndex + '">Key:</label> <input type="text" id="key' + fieldIndex + '" name="key' + fieldIndex + '"> <label for="value' + fieldIndex + '">Value:</label> <input type="text" id="value' + fieldIndex + '" name="value' + fieldIndex + '"> <button type="button" onclick="deleteKeyValueField(this)">Delete</button>';
              keyValueFields.appendChild(newFields);
              fieldIndex++;
          }
          function deleteKeyValueField(button) {
              var keyValueField = button.parentElement;
              keyValueField.remove();
          }
          var fieldIndexBody = <?php echo esc_js($m); ?>;
          function addKeyValueFieldBody() {
          var keyValueFieldsBody = document.getElementById('key-value-fields-body');
          var newFieldsBody = document.createElement('div');
          newFieldsBody.innerHTML = '<label for="key' + fieldIndexBody + '_body">Key:</label> <input type="text" id="key' + fieldIndexBody + '_body" name="key' + fieldIndexBody + '_body"> <label for="value' + fieldIndexBody + '_body">Value:</label> <input type="text" id="value' + fieldIndexBody + '_body" name="value' + fieldIndexBody + '_body"> <button type="button" onclick="deleteKeyValueFieldBody(this)">Delete</button>';
          keyValueFieldsBody.appendChild(newFieldsBody);
          fieldIndexBody++;
          }
          function deleteKeyValueFieldBody(button) {
          var keyValueFieldBody = button.parentElement;
          keyValueFieldBody.remove();
          }
          </script>
<?php
    if (isset($_POST['endpoint'])) {
       $method = sanitize_text_field( $_POST['method'] );

       // Create an array to store the headers
       $headers = array();

       // Iterate through the 'key' and 'value' input fields and add each 'key' and 'value' pair to the $headers array
       foreach ($_POST as $key => $value) {
           if (strpos($key, 'key') === 0 && !empty($value)) {
               // Get the corresponding value for this key
               $index = substr($key, 3);
               $value_key = 'value' . $index;

               // Sanitize the key and value
               $value = sanitize_text_field($value);
               $value_key = sanitize_text_field($_POST[$value_key]);

               // Add the key and value to the $headers array
               $headers[$value] = $value_key;
           }
       }

       // Create an array to store the body
       $body = array();

       // Iterate through the 'key' and 'value' input fields for the body and add each 'key' and 'value' pair to the $body array
          foreach ($_POST as $key => $value) {
              if (strpos($key, 'key') === 0 && strpos($key, '_body') && !empty($value)) {
                  // Get the corresponding value for this key
                  $index = substr($key, 3, -5);
                  $value_key = 'value' . $index . '_body';

                  // Sanitize the key and value
                  $value = sanitize_text_field($value);
                  $value_key = sanitize_text_field($_POST[$value_key]);

                  // Add the key and value to the $body array
                  $body[$value] = $value_key;
              }
          }

       // Set up the API request
       $args = array(
           'method' => $method,
           'headers' => $headers,
           'body' => $body,
       );
       $start = microtime(true);
       $response = wp_remote_request($endpoint, $args);
       $end = microtime(true);

       // Calculate the response latency
       $latency = round(($end - $start) * 1000);

       // Check the response status
       $response_code = wp_remote_retrieve_response_code($response);
       if (is_wp_error($response)) {
           $status = 'ERROR';
           $body = $response->get_error_message();
       } else {
           $status = $response_code . ' ' . get_status_header_desc($response_code);
           $body = wp_remote_retrieve_body($response);
       }

       // Display the response status, body, and latency
       ?>
       <div class="status">
       </br>
       <?php
       $body = strip_tags($body);
       echo '<p>Status: ' . esc_html($status) . '</p>';
       echo '<p>Latency: ' . esc_html($latency) . 'ms</p>';
       echo 'Response Body: ';
       echo '<pre>';
       echo json_encode(json_decode($body), JSON_PRETTY_PRINT);
       echo '</pre>';
       ?>
       </div>
       </div>
       <style type="text/css">
           .form-container {
             display: flex; /* Display the form and the "Status" section side by side */
           }

           .form-container form {
             flex: 1; /* Make the form take up the left half of the container */
           }

           .form-container .status {
             flex: 1; /* Make the "Status" section take up the right half of the container */
             max-height: 400px;
             overflow: auto;
             padding-left: 50px;
             border-left: 1px solid #ccc;
           }
         </style>
       <?php
       
    }

}