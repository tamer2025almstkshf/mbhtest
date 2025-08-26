<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class IntegrationTest extends TestCase
{
    protected static $httpClient;
    protected static $baseUrl = 'http://localhost:8080'; // Updated to match docker-compose.yml

    public static function setUpBeforeClass(): void
    {
        self::$httpClient = new Client([
            'base_uri' => self::$baseUrl,
            'http_errors' => false, // We want to handle HTTP errors manually in tests
        ]);

        // Load .env variables for the test environment
        // The Dotenv library will automatically load .env if it exists,
        // and environment variables set in phpunit.xml will override it.
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();
    }

    public function testDatabaseConnection()
    {
        // Include the connection file to test its functionality
        // We'll use getenv() which will pick up variables from phpunit.xml or .env
        $servername = getenv('DB_HOST');
        $username   = getenv('DB_USER');
        $password   = getenv('DB_PASS');
        $dbname     = getenv('DB_NAME');

        // Create the one and only database connection object.
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Assert that the connection object exists and is a mysqli instance
        $this->assertNotNull($conn);
        $this->assertInstanceOf(mysqli::class, $conn);

        // Assert that there are no connection errors
        $this->assertEquals(0, $conn->connect_errno, 'Database connection failed: ' . $conn->connect_error);

        // Try a simple query to ensure the connection is truly working
        $result = $conn->query("SELECT 1");
        $this->assertNotFalse($result, 'Failed to execute a simple database query.');
        $result->free();

        // Close the connection explicitly for the test
        $conn->close();
    }

    public function testGetClientApiEndpointSuccess()
    {
        // Make a GET request to the api/getClient.php endpoint
        // Assuming a client with ID 1 exists in your test database
        $response = self::$httpClient->get('/api/getClient.php?id=1');

        // Assert the HTTP status code is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Decode the JSON response
        $data = json_decode($response->getBody(), true);

        // Assert that the response status is 'success'
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('success', $data['status']);

        // Assert that the 'data' key exists and contains client information
        $this->assertArrayHasKey('data', $data);
        $this->assertIsArray($data['data']);
        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals(1, $data['data']['id']); // Assert the ID is 1 (or whatever you expect)
        // Add more assertions for other expected fields if needed
        $this->assertArrayHasKey('arname', $data['data']);
        $this->assertArrayHasKey('engname',
         $data['data']);
    }

    public function testGetClientApiEndpointNotFound()
    {
        // Make a GET request for a client that does not exist
        $response = self::$httpClient->get('/api/getClient.php?id=99999'); // Assuming ID 99999 does not exist

        // Assert the HTTP status code is 200 (OK) even for 'not found' as the script returns JSON with a status
        $this->assertEquals(200, $response->getStatusCode());

        // Decode the JSON response
        $data = json_decode($response->getBody(), true);

        // Assert that the response status is 'error' and the message indicates 'Client not found.'
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('error', $data['status']);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Client not found.', $data['message']);
    }

    public function testGetClientApiEndpointMissingId()
    {
        // Make a GET request without an 'id' parameter
        $response = self::$httpClient->get('/api/getClient.php');

        // Assert the HTTP status code is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Decode the JSON response
        $data = json_decode($response->getBody(), true);

        // Assert that the response status is 'error' and the message indicates 'Invalid request.'
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('error', $data['status']);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Invalid request.', $data['message']);
    }
}
