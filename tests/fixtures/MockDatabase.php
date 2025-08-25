<?php
class MockResult {
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function fetch_assoc() {
        return $this->data;
    }
}

class MockStmt {
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function bind_param($types, &...$vars) {
        // No-op for mock
    }
    public function execute() {
        return true;
    }
    public function get_result() {
        return new MockResult($this->data);
    }
    public function close() {
        // No-op for mock
    }
}

class MockMySQLi {
    private $queue;
    public function __construct(array $queue) {
        $this->queue = $queue;
    }
    public function prepare($query) {
        $data = array_shift($this->queue);
        return new MockStmt($data);
    }
}
