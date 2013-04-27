<?php
    require_once '../engine/BMInterface.php';

    $interface = new BMInterface;

    header('Content-Type: application/json');

    switch ($_POST['type']) {
        case 'loadButtonNames':
            $dataArray = array(
                'buttonNameArray' => $interface->get_all_button_names());
            echo json_encode($dataArray);
            break;
        case 'chooseButtons':
            $buttonNameArray = $_POST['buttonNameArray'];
            echo json_encode(array('status' => 'ok',
                                   'data' => $buttonNameArray));
            break;
        case 'submitSwingValues':
            echo json_encode(array('status' => 'ok', 'data' => 'hello'));
            break;
        default:
            //do nothing
    }
?>
