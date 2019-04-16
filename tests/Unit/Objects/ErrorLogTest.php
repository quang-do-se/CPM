<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 10:32 AM
 */

namespace Tests\Unit\Objects;

use App\CPM\Objects\ErrorLog;
use PHPUnit\Framework\TestCase;

class ErrorLogTest extends TestCase
{
    public function testLogIsEmpty()
    {
        $errorLog = new ErrorLog();

        $this->assertFalse($errorLog->hasError());
    }

    public function testCanAddErrorToLog()
    {
        $errorLog = new ErrorLog();
        $errorLog->addMessage('Something is wrong');

        $this->assertTrue($errorLog->hasError());
    }

    public function testCanRetrieveError()
    {
        $message0 = 'Something is wrong.';
        $message1 = 'Something is not right.';

        $errorLog = new ErrorLog();
        $errorLog->addMessage($message0);
        $errorLog->addMessage($message1);

        $errors = $errorLog->getErrors();

        $this->assertEquals($message0, $errors[0]);
        $this->assertEquals($message1, $errors[1]);
    }

    public function testEmptyingLog()
    {
        $message0 = 'Something is wrong.';
        $message1 = 'Something is not right.';

        $errorLog = new ErrorLog();
        $errorLog->addMessage($message0);
        $errorLog->addMessage($message1);

        $errorLog->empty();

        $this->assertFalse($errorLog->hasError());
    }


    public function testMergingErrorLog()
    {
        $errorLogA = new ErrorLog();

        $errorLogA->addMessage('Wrong');
        $errorLogA->addMessage('No');

        $errorLogB = new ErrorLog();

        $errorLogB->addMessage('Incorrect');
        $errorLogB->addMessage('Failed');

        $errorLogA->addLog($errorLogB);

        $this->assertCount(4, $errorLogA->getErrors());
    }
}
