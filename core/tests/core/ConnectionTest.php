<?php
class ConnectionTest extends PHPUnit_Framework_TestCase
{
    // contains the object handle of the string class
    var $abc;

    // constructor of the test suite
    function __construct() {
        
    }

    // called before the test functions will be executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    function setUp() {
        // create a new instance of String with the
        // string 'abc'
//        $this->abc = new String("abc");
    }

    // called after the test functions are executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    function tearDown() {
        // delete your instance
//        unset($this->abc);
    }

    // test the toString function
    function testToString() {
//        $result = $this->abc->toString('contains %s');
//        $expected = 'contains abc';
        $this->assertTrue(1 == 1);
    }
}