<?php


namespace App\Tests\Unit\Security\Voter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Complexity\Calculator;

// #[CoversClass(Calculator::class)]
        class ExempleTest extends TestCase
   {

 #[Test]
 public function AdditionTest()
                {
$data = 2;

$this->assertEquals(2,$data);

                }
#[Test]
 public function MultiplicationTest()
 {
    $this->assertEquals(16,4*4);
 }               
        }