<?php
// include 'config.php';

function khalti_checkout($product, $user){
    $id = $_SESSION['id'];
    $product_id = $product['product_id'] ?? '';
    $quantity = (int) $product['quantity'];
    $price = (int)$product['offer'];
    $total = $price*$quantity*100;
    $name = $user['username'];
    $address = $user['address'];
    $contact = $user['contact'];
    $title = $product['title'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "return_url": "http://localhost/Mero-Pasal/view_cart.php",
    "website_url": "http://localhost/mero-Pasal/",
    "amount": '.$total.',
    "purchase_order_id": "'.$product_id.'",
        "purchase_order_name": "'.$title.'",

    "customer_info": {
        "name": "'.$name.'",
        "email": "test@khalti.com",
        "phone": "'.$contact.'"
    }
    }

    ',
    CURLOPT_HTTPHEADER => array(
        'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $res = json_decode($response);
    include 'config.php';

    // file_put_contents( _DIR_ . '/error.txt',  print_r( $response, true ) . PHP_EOL, FILE_APPEND  );
    if (!empty($res->payment_url)) {
        $cart_id = $product['id'] ?? '';
        $product_id = $product['product_id'] ?? '';
        $quantity = (int) $product['quantity'];
        $price = (int)$product['price'];
        $total = $price*$quantity;
        $name = $user['username'];
        $address = $user['address'];
        $contact = $user['contact'];
        $payment = $product['payment'] ?? 'khalti';

        if(!$cart_id || !$product_id || !$quantity || !$total || !$name || !$address || !$contact){
            echo "Missing fields"; exit;
        }

        // Insert into orders
        $stmt = $pdo->prepare("INSERT INTO orders 
        (user_id, product_id, quantity, total, name, address, contact, payment, payment_status, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $id, 
            $product_id, 
            $quantity, 
            $total, 
            $name, 
            $address, 
            $contact, 
            $payment ,          // payment method
            'Paid',       // payment_status
            'Pending'        // order status
        ]);

        // Remove from cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
        $stmt->execute([$cart_id, $id]);
    header("Location: " . $res->payment_url);
    exit;
    } else {
        echo "Payment failed try again.";
    }
}
    