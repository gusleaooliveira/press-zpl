<!DOCTYPE html>
<html>
<header>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.css" integrity="sha512-L7yJn5vPNKXzMFb05k0sDfT0VLYo1z5LOHb3MfXlp3/7t0PH4vwQZvMY21xtMeeZu/Pe0ed1u22qAw9Cs2HDsQ==" crossorigin="anonymous" />

</header>

<body>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedOrderId = $_POST["order_id"];
        $sql = "SELECT * FROM service_order WHERE order_id = $selectedOrderId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            require('list_data.php');
            require('generate_zpl.php');

            $postition = 35;
            $zplContent = "^XA ^CF0,12,12\n";

            while ($row = $result->fetch_assoc()) {
                $fields = ['Ordem' => $row['order_id'], 'Endereço de entrega' => $row['delivery_address'], 'Números de telefone' => $row['phones'], 'Emitida pela' => $row['issued_by'], 'Código localizador' => $row['locator'], 'Data de conclusão' => $row['completion_date'], 'Nome do cliente' => $row['name'], 'Quantidade' => $row['quantity'], 'Modelo/Tamanho' => $row['model_size'], 'Configuração do produto' => $row['product_configuration'], 'Embalagem' => $row['packaging'], 'Frete' => $row['freight']];

                foreach ($fields as $label => $value) {
                    $zplContent .= listData($label . ":", $value, $postition);
                    $postition = calculate($value, $postition);
                }
            }
            $zplContent .= "^XZ";

            generateZpl($zplContent);
        } else {
            echo "No results for order ID $selectedOrderId";
        }
    }

    $distinctOrderIdsQuery = "SELECT DISTINCT order_id FROM service_order";
    $distinctOrderIdsResult = $conn->query($distinctOrderIdsQuery);
    $orderIds = [];

    if ($distinctOrderIdsResult->num_rows > 0) {
        while ($row = $distinctOrderIdsResult->fetch_assoc()) {
            $orderIds[] = $row['order_id'];
        }
    }

    $conn->close();
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="order_id">Select Order ID:</label>
        <select name="order_id" id="order_id">
            <?php foreach ($orderIds as $orderId) {
                echo "<option value=\"$orderId\">$orderId</option>";
            } ?>
        </select>
        <input type="submit" value="Generate ZPL Label">
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.js" integrity="sha512-o4xkb4QLe+J4q+dX7WgP2+w1H1G8+55gBR/D74SzI19IczF60leRF9Um0iXtrPZMFDc3pHSF4R+1nWcTJT7ZoA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-markup.min.js" integrity="sha512-btLUHR4Ky4VXV5i5EZweeVVvj5Lr1+sr7d4mt1O6opFo09BxGjw0I5g3fF5Jf0bqjDl6LPveMAMXNHlZx/MyCw==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/line-numbers/prism-line-numbers.min.js" integrity="sha512-g30MYUuWNlQjz1Ad2/XcD0+V5qlD80Q3HcbnYI/vv4bWbVZ2MEVHqQfnmoiAYPVQlTGjlQu0aw4AIO2Do6kgDw==" crossorigin="anonymous"></script>

</body>

</html>