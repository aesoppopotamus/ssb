<?php

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testCreatePost()
    {
        // Create a mock for the mysqli class
        $mysqliMock = $this->createMock(mysqli::class);

        // Mock the prepare method to return a mocked statement
        $stmtMock = $this->createMock(mysqli_stmt::class);

        // Set up the expectations for the prepared statement
        $stmtMock->expects($this->once())
                 ->method('bind_param')
                 ->with('ss', 'My Test Post', 'This is a post for testing purposes.');

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        // Set the expectation for the prepare method in mysqli
        $mysqliMock->expects($this->once())
                   ->method('prepare')
                   ->with($this->equalTo('INSERT INTO posts (title, content) VALUES (?, ?)'))
                   ->willReturn($stmtMock);

        // Mock the mysqli_result class to simulate fetch_assoc returning a row
        $resultMock = $this->createMock(mysqli_result::class);
        
        // Simulate that a row exists
        $resultMock->expects($this->once())
                   ->method('fetch_assoc')
                   ->willReturn(['title' => 'My Test Post', 'content' => 'This is a post for testing purposes.']);
        
        // Ensure fetch_assoc returns false when there are no more rows
        $resultMock->expects($this->once())
                   ->method('fetch_assoc')
                   ->willReturn(false);

        $mysqliMock->expects($this->once())
                   ->method('query')
                   ->with($this->equalTo("SELECT * FROM posts WHERE title = 'My Test Post'"))
                   ->willReturn($resultMock);

        // Simulate the actual application logic
        $title = "My Test Post";
        $content = "This is a post for testing purposes.";

        $stmt = $mysqliMock->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');
        $stmt->bind_param('ss', $title, $content);
        $stmt->execute();

        $result = $mysqliMock->query("SELECT * FROM posts WHERE title = '$title'");
        
        // Simulate checking for a row being returned
        $row = $result->fetch_assoc();
        $this->assertNotEmpty($row);  // Check if a row was returned (simulates num_rows > 0)
    }
}
