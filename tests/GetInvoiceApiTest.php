<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/fixtures/MockDatabase.php';

class GetInvoiceApiTest extends TestCase
{
    private function runEndpoint($connMock, array $getParams): array
    {
        global $conn;
        $conn = $connMock;
        $_GET = $getParams;
        $_SESSION['id'] = 1;

        ob_start();
        include __DIR__ . '/../api/getInvoice.php';
        $output = ob_get_clean();
        return json_decode($output, true);
    }

    public function testSuccess()
    {
        $connMock = new MockMySQLi([
            ['id' => 1, 'name' => 'User'],
            ['id' => 1, 'amount' => 100]
        ]);
        $response = $this->runEndpoint($connMock, ['id' => 1]);
        $this->assertEquals('success', $response['status']);
        $this->assertEquals(1, $response['data']['id']);
    }

    public function testNotFound()
    {
        $connMock = new MockMySQLi([
            ['id' => 1, 'name' => 'User'],
            null
        ]);
        $response = $this->runEndpoint($connMock, ['id' => 99]);
        $this->assertEquals('error', $response['status']);
        $this->assertEquals('Invoice not found.', $response['message']);
    }

    public function testMissingId()
    {
        $connMock = new MockMySQLi([
            ['id' => 1, 'name' => 'User']
        ]);
        $response = $this->runEndpoint($connMock, []);
        $this->assertEquals('error', $response['status']);
        $this->assertEquals('Invalid request.', $response['message']);
    }
}
