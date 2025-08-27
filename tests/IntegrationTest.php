<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class IntegrationTest extends TestCase
{
    protected static $httpClient;
    protected static $baseUrl = 'http://localhost:8080'; // Updated to match docker-compose.yml
    protected static $skip = false;

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

        // Check if the database is reachable; if not, skip all tests in this class
        try {
            $conn = @new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
            if ($conn->connect_errno) {
                self::$skip = true;
                return;
            }
            $conn->close();
        } catch (\mysqli_sql_exception $e) {
            self::$skip = true;
            return;
        }

        // Check if the API is reachable; if not, skip all tests
        try {
            self::$httpClient->get('/');
        } catch (ConnectException $e) {
            self::$skip = true;
        }
    }

    protected function setUp(): void
    {
        if (self::$skip) {
            $this->markTestSkipped('Integration tests require running database and API services');
        }
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
        try {
            $conn = @new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_errno) {
                $this->markTestSkipped('Database not available: ' . $conn->connect_error);
            }
        } catch (\mysqli_sql_exception $e) {
            $this->markTestSkipped('Database not available: ' . $e->getMessage());
        }

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
        try {
            $response = self::$httpClient->get('/api/getClient.php?id=1');
        } catch (ConnectException $e) {
            $this->markTestSkipped('API not available: ' . $e->getMessage());
        }

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
        try {
            $response = self::$httpClient->get('/api/getClient.php?id=99999'); // Assuming ID 99999 does not exist
        } catch (ConnectException $e) {
            $this->markTestSkipped('API not available: ' . $e->getMessage());
        }

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
        try {
            $response = self::$httpClient->get('/api/getClient.php');
        } catch (ConnectException $e) {
            $this->markTestSkipped('API not available: ' . $e->getMessage());
        }

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
