<?php
    function formatarData($data) {
        $data = explode('-', $data);
        $data = implode("/", array_reverse($data));
        return $data;
    }

    function mesPortugues($mes) {
        switch ($mes) {
            case '01':
                return "Janeiro";
                break;
            case '02':
                return "Fereveiro";
                break;
            case '03':
                return "Março";
                break;
            case '04':
                return "Abril";
                break;
            case '05':
                return "Maio";
                break;
            case '06':
                return "Junho";
                break;
            case '07':
                return "Julho";
                break;
            case '08':
                return "Agosto";
                break;
            case '09':
                return "Setembro";
                break;
            case '10':
                return "Outubro";
                break;
            case '11':
                return "Novembro";
                break;
            case '12':
                return "Dezembro";
                break;
            default:
                return "";
                break;
        }
    }

?>