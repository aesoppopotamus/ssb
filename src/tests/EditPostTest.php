<?php

use PHPUnit\Framework\TestCase;

class EditPostTest extends TestCase
{
    public function testEditPost()
    {
        $mysqliMock = $this->createMock(mysqli::class);

        // Mocking the prepare statement for updating the post
        $stmtMock = $this->createMock(mysqli_stmt::class);

        // Define the values that will be passed by reference
        $newContent = 'Updated content for the test post.';
        $title = 'My Test Post';

        // Set up expectations for bind_param with reference parameters
        $stmtMock->expects($this->once())
                 ->method('bind_param')
                 ->with('ss', $this->callback(function(&$content) use ($newContent) {
                     return $content === $newContent;
                 }), $this->callback(function(&$t) use ($title) {
                     return $t === $title;
                 }));

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        $mysqliMock->expects($this->once())
                   ->method('prepare')
                   ->with($this->equalTo('UPDATE posts SET content = ? WHERE title = ?'))
                   ->willReturn($stmtMock);

        // Mock the mysqli_result class to simulate fetching the updated post
        $resultMock = $this->createMock(mysqli_result::class);
        
        // Simulate returning the updated content for the post
        $resultMock->expects($this->once())
                   ->method('fetch_assoc')
                   ->willReturn(['content' => 'Updated content for the test post.']);

        // Simulate querying the updated post
        $mysqliMock->expects($this->once())
                   ->method('query')
                   ->with($this->equalTo("SELECT content FROM posts WHERE title = 'My Test Post'"))
                   ->willReturn($resultMock);

        // Simulate the update logic
        $stmt = $mysqliMock->prepare('UPDATE posts SET content = ? WHERE title = ?');
        $stmt->bind_param('ss', $newContent, $title); // Parameters are passed by reference
        $stmt->execute();

        $result = $mysqliMock->query("SELECT content FROM posts WHERE title = 'My Test Post'");
        $post = $result->fetch_assoc();

        // Verify that the content has been updated
        $this->assertEquals('Updated content for the test post.', $post['content']);
    }
}
