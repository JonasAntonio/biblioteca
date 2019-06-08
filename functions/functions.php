<?php
    function formatarData($data) {
        $data = explode('-', $data);
        $data = implode("/", array_reverse($data));
        return $data;
    }
?>