<?php
session_start();

require_once '../src/auxiliar.php';

unset($_SESSION['carrito']);

volver();
