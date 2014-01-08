<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Spir\Dotclear\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    private $xmlRpcClient;

    public function setUp()
    {
        $this->xmlRpcClient = $this->getMockBuilder('fXmlRpc\ClientInterface')->getMock();
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testSetUrlWithNoData()
	{
		// Given
		$url=null;
		$username=null;
		$password=null;
		$blogId=null;
		$xmlRpcClient=null;
		$client = new Client($url, $username, $password, $blogId, $xmlRpcClient);
		
		// When
		$client->setUrl($url);
		
		// Then
		// InvalidArgumentException
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testSetUsernameWithNoData()
	{
		// Given
		$url=null;
		$username=null;
		$password=null;
		$blogId=null;
		$xmlRpcClient=null;
		$client = new Client($url, $username, $password, $blogId, $xmlRpcClient);
		
		// When
		$client->setUsername($username);
		
		// Then
		// InvalidArgumentException
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testSetPasswordWithNoData()
	{
		// Given
		$url=null;
		$username=null;
		$password=null;
		$blogId=null;
		$xmlRpcClient=null;
		$client = new Client($url, $username, $password, $blogId, $xmlRpcClient);
		
		// When
		$client->setPassword($password);
		
		// Then
		// InvalidArgumentException
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testSetBlogIdWithNoData()
	{
		// Given
		$url=null;
		$username=null;
		$password=null;
		$blogId=null;
		$xmlRpcClient=null;
		$client = new Client($url, $username, $password, $blogId, $xmlRpcClient);
		
		// When
		$client->setBlogId($blogId);
		
		// Then
		// InvalidArgumentException
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testCreatePostWithWrongArguments() 
	{
		// Given
		$client = new Client();
		$title = $excerpt = $content = $publish = $slug = $categoryId = null;
		
		// When
		$client->createPost($title, $excerpt, $content, $publish, $slug, $categoryId);
		
		// Then
		// InvalidArgumentException
	}
    
    /**
     * @expectedException Exception
     */
	public function testCreatePostWithXmlRpcFailure() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
	    $xmlRpcClientResponse = 1;
	    $excerpt = $publish = $slug = $categoryId = null;
	    $title = 'Title';
	    $content = 'Lorem Ipsum';
	    $publish = 1;
	    
	    $this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('metaWeblog.newPost', Array($blogId, $username, $password,
					Array(
							'title' => $title,
							'mt_excerpt' => $excerpt,
							'description' => $content,
							'wp_slug' => $slug,
							'categories' => Array((string)$categoryId),
							'post_type' => 'post'
					), $publish,))
			->will($this->throwException(new \fXmlRpc\Exception\TransportException));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		
		// When
		$result = $client->createPost($title, $excerpt, $content, $publish, $slug, $categoryId);
		
		// Then
		// Exception
	}
	
	public function testCreatePostWithMinimumInformations() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
	    $xmlRpcClientResponse = 1;
	    $excerpt = $publish = $slug = $categoryId = null;
	    $title = 'Title';
	    $content = 'Lorem Ipsum';
	    $publish = 1;
	    
	    $this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('metaWeblog.newPost', Array($blogId, $username, $password,
					Array(
							'title' => $title,
							'mt_excerpt' => $excerpt,
							'description' => $content,
							'wp_slug' => $slug,
							'categories' => Array((string)$categoryId),
							'post_type' => 'post'
					), $publish,))
	        ->will($this->returnValue($xmlRpcClientResponse));
	    
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		
		// When
		$result = $client->createPost($title, $excerpt, $content, $publish, $slug, $categoryId);
		
		// Then
		$this->assertTrue($result==$xmlRpcClientResponse);
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testGetPostWithNoId() 
	{
		// Given
		$client = new Client();
		$id = null;
		
		// When
		$client->getPost($id);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testGetPostWithId() 
	{
		// Given
		$username = 'username';
		$password = 'password';
		$expectedResult = new stdClass;
		$id = 1;

		$this->xmlRpcClient->expects($this->once())->method('call')
		    ->with('metaWeblog.getPost', Array($id, $username, $password))
		    ->will($this->returnValue($expectedResult));
		
		$client = new Client(null, $username, $password, null, $this->xmlRpcClient);
		
		// When
		$result = $client->getPost($id);
		
		// Then
		$this->assertTrue($expectedResult==$result);
	}
	
	public function testGetPostsWithDefaultLimit() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$expectedResult = Array(
		    new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, 
		    new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, new stdClass);

		$this->xmlRpcClient->expects($this->once())->method('call')
		    ->with('metaWeblog.getRecentPosts', Array($blogId, $username, $password, 10))
		    ->will($this->returnValue($expectedResult));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->getPosts();
		
		// Then
		$this->assertTrue($expectedResult==$result);
	}
	
	public function testGetPostsWithNoLimit() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$expectedResult = Array(
		    new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, 
		    new stdClass, new stdClass, new stdClass, new stdClass, new stdClass, new stdClass);

		$this->xmlRpcClient->expects($this->once())->method('call')
		    ->with('metaWeblog.getRecentPosts', Array($blogId, $username, $password, null))
		    ->will($this->returnValue($expectedResult));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->getPosts(0);
		
		// Then
		$this->assertTrue($expectedResult==$result);
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testUpdatePostWithWrongArguments() 
	{
		// Given
		$client = new Client();
		$id = $title = $excerpt = $content = $publish = $slug = $categoryId = null;
		
		// When
		$client->updatePost($id, $title, $excerpt, $content, $publish, $slug, $categoryId);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testUpdatePostWithMinimumInformations() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
	    $xmlRpcClientResponse = 1;
	    $excerpt = $publish = $slug = $categoryId = null;
	    $id = 1;
	    $title = 'Title';
	    $content = 'Lorem Ipsum';
	    $publish = 1;
	    
	    $this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('metaWeblog.editPost', Array($id, $username, $password,
					Array(
							'title' => $title,
							'mt_excerpt' => $excerpt,
							'description' => $content,
							'wp_slug' => $slug,
							'categories' => Array(Array('categoryId'=>(string)$categoryId)),
							'post_type' => 'post'
					), $publish,))
	        ->will($this->returnValue($xmlRpcClientResponse));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		
		// When
		$result = $client->updatePost($id, $title, $excerpt, $content, $publish, $slug, $categoryId);
		
		// Then
		$this->assertTrue($result==$xmlRpcClientResponse);
	}
	
	// TODO test a 404 on update
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testDeletePostWithNoId() 
	{
		// Given
		$client = new Client();
		$id = null;
		
		// When
		$client->deletePost($id);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testDeletePostWithCorrectId() 
	{
		// Given
		$username = 'username';
		$password = 'password';
		$id = 1;
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('blogger.deletePost', Array('', $id, $username, $password, 1))
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, null, $this->xmlRpcClient);
		
		// When
		$result = $client->deletePost($id);
		
		// Then
		$this->assertTrue($result);
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testSetPostCategoryWithNoIds() 
	{
		// Given
		$client = new Client();
		$postId = null;
		$categoryId = null;
		
		// When
		$client->setPostCategory($postId, $categoryId);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testSetPostCategoryWithIds() 
	{
		// Given
		$username = 'username';
		$password = 'password';
		$postId = 1;
		$categoryId = 1;
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('mt.setPostCategories', 
	            Array(
	                $postId, $username, $password, 
	                Array(Array('categoryId'=>$categoryId)))
	            )
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, null, $this->xmlRpcClient);
		
		// When
		$result = $client->setPostCategory($postId, $categoryId);
		
		// Then
		$this->assertTrue($result);
	}
	
	// TODO test a 404 on set post category id on category/post
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testCreateCategoryWithWrongArguments() 
	{
		// Given
		$client = new Client();
		$name = $slug = $description = $parentId = null;
		
		// When
		$client->createCategory($name, $slug, $description, $parentId);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testCreateCategoryWithCorrectArguments() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$name = 'Category name';
		$description = 'Category description';
		$slug = $parentId = null;
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('wp.newCategory', Array(
	                $blogId, $username, $password,
				    Array(
					    'name' => $name,
					    'slug' => $slug,
					    'category_description' => $description,
					    'category_parent' => $parentId,
				    )))
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->createCategory($name, $slug, $description, $parentId);
		
		// Then
		$this->assertTrue($result);
	}
	
	public function testGetCategories() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('wp.getCategories', Array($blogId, $username, $password,))
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->getCategories();
		
		// Then
		$this->assertTrue($result);
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testDeleteCategoryWithNoId() 
	{
		// Given
		$client = new Client();
		$id = null;
		
		// When
		$client->deleteCategory($id);
		
		// Then
		// InvalidArgumentException
	}
	
	public function testDeleteCategoryWithCorrectId() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$id = 1;
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('wp.deleteCategory', Array($blogId, $username, $password, $id))
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->deleteCategory($id);
		
		// Then
		$this->assertTrue($result);
	}
	
	public function testGetAuthors() 
	{
		// Given
		$blogId = 'blog id';
		$username = 'username';
		$password = 'password';
		$this->xmlRpcClient->expects($this->once())->method('call')
	        ->with('wp.getAuthors', Array($blogId, $username, $password,))
	        ->will($this->returnValue(true));
		
		$client = new Client(null, $username, $password, $blogId, $this->xmlRpcClient);
		
		// When
		$result = $client->getAuthors();
		
		// Then
		$this->assertTrue($result);
	}
    
    /**
     * @expectedException InvalidArgumentException
     */
	public function testNewMediaWithNoFilename() 
	{
		// Given
		$filename = '';
		$uri = '';
		$client = new Client();
		
		// When
		$client->newMedia($filename, $uri);
		
		// Then
		// InvalidArgumentException
	}
	
}
