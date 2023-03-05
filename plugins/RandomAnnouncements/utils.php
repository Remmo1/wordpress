<?php

function convert_object_to_array($obj) {
    return is_array($obj) ? $obj : get_object_vars($obj);
}