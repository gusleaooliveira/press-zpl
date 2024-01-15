<?php
function generateZpl($zplContent) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/",
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $zplContent,
        CURLOPT_RETURNTRANSFER => TRUE
    ]);

    $result = curl_exec($curl);

    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
        $file = fopen("label.png", "w");
        fwrite($file, $result);
        fclose($file);

        echo '<table style="width:100%;">';
        echo "<tr>";
        echo '<td>
                <img src="label.png" alt="Generated Label" style="max-width: 100%; height: auto;">
            </td>';
        echo '<td>
                <pre><code class="language-zpl">' . htmlentities($zplContent) . '</code></pre>
            </td>';
        echo "</tr>";
        echo "</table>";

        // Adicionando script JavaScript para download automático e impressão
        echo '
            <script>
                function downloadAndPrint() {
                    var link = document.createElement("a");
                    link.href = "label.png";
                    link.download = "label.png";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    printLabel();
                }

                function printLabel() {
                    // Adicione aqui a lógica JavaScript para acionar a impressão da imagem
                    // Exemplo: window.print();
                }

                // Chame a função automaticamente após a exibição da imagem
                downloadAndPrint();
            </script>';
    } else {
        print_r("Error: $result");
    }

    curl_close($curl);
}
?>
