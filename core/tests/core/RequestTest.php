<?php
require_once 'Request.php';
class RequestTest extends PHPUnit_Framework_TestCase{
    //contains the object handle of the string class
    var $request;

    // Función de PHPUnit que se ejecuta antes de los tests
    function setUp() {
        $this->request=new Request("/controller/function/parameter1/parameter2/");
    }

    // Función de PHPUnit que se ejecuta luego de ejecutar los tests
    function tearDown() {
        unset($this->request);
    }

    // Tests de los métodos de la clase
    function testGetController() {
        $controller=$this->request->getController();
        $expected='controller';
        $this->assertTrue($controller==$expected);
    }
}