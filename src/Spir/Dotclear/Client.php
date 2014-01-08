<?php namespace Spir\Dotclear;

use \Exception;
use \InvalidArgumentException;
use fXmlRpc\ClientInterface as XmlRpcClientInterface;
use fXmlRpc\Client as XmlRpcClient;

class Client implements ClientInterface
{

    /**
     * XML RPC client object
     * @var fXmlRpc\Client
     */
    protected $blog;

    /**
     * URL of the remote blog
     * @var string
     */
    protected $url;

    /**
     * Blog login username
     * @var string
     */
    protected $username;

    /**
     * Blog password
     * @var string
     */
    protected $password;

    /**
     * Blog ID
     * @var string
     */
    protected $blogId;

    /**
     * Constructor
     *
     * @param string $url
     * @param string $username
     * @param string $password
     * @param string $blogId
     */
    public function __construct($url=null, $username=null, $password=null, $blogId=null, XmlRpcClientInterface $client=null)
    {
        $this->blog = $client ?: new XmlRpcClient($this->url);

        if ($url !== NULL)
            $this->setUrl($url);

        if ($username !== NULL)
            $this->setUsername($username);

        if ($password !== NULL)
            $this->setPassword($password);

        if ($blogId !== NULL)
            $this->setBlogId($blogId);
    }

    /**
     * Set the XML-RPC url
     *
     * @param string $url
     * @throws InvalidArgumentException
     */
    public function setUrl($url=null)
    {
        if ($url === NULL)
            throw new InvalidArgumentException('URL is not valid');

        $this->url = $url;
        $this->blog->setUri($this->url);
    }

    /**
     * Set the blog username
     *
     * @param string $username
     * @throws InvalidArgumentException
     */
    public function setUsername($username=null)
    {
        if ($username === NULL)
            throw new InvalidArgumentException('username is empty');

        $this->username = $username;
    }

    /**
     * Set the blog password
     *
     * @param string $password
     * @throws InvalidArgumentException
     */
    public function setPassword($password=null)
    {
        if ($password === NULL)
            throw new InvalidArgumentException('password is empty');

        $this->password = $password;
    }

    /**
     * Set the blog id to discuss with
     *
     * @param string $blogId
     * @throws InvalidArgumentException
     */
    public function setBlogId($blogId=null)
    {
        if ($blogId === NULL)
            throw new InvalidArgumentException('blog is is empty');

        $this->blogId = $blogId;
    }

    /**
     * Create a new post using metaWeblog.newPost
     * More infos : http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.newPost
     *
     * @param string $title title of the post
     * @param string $excerpt excerpt of the post
     * @param string $content content of the post
     * @param boolean $publish publish Yes/No
     * @return Mixed <boolean, integer> Return false if failure or Post ID if success
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function createPost($title=null, $excerpt=null, $content=null, $publish=false, $slug=null, $categoryId=null)
    {
        if ($title === NULL)
            throw new InvalidArgumentException('post title is missing');

        if ($content === NULL)
            throw new InvalidArgumentException('post content is missing');

        try
        {
            return $this->call('metaWeblog.newPost', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password,
                Array(
                    'title' => $title,
                    'mt_excerpt' => $excerpt,
                    'description' => $content,
                    'wp_slug' => $slug,
                    'categories' => Array((string)$categoryId),
                    'post_type' => 'post'
                ),
                $publish,
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Return all pages/posts from a selected blog
     * More infos: http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.getPost
     *
     * @param integer $id
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function getPost($id=null)
    {
        if ($id === NULL)
            throw new InvalidArgumentException('post id is missing');

        try
        {
            return $this->call('metaWeblog.getPost', Array(
                (string)$id,
                (string)$this->username,
                (string)$this->password
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get all recent posts
     * More infos: http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.getRecentPosts
     *
     * @param integer $limit maximum post to retrieve
     * @throws Exception
     */
    public function getPosts($limit=10)
    {
        $limit = intval($limit);
        if ($limit==0)
            $limit = null;
         
        try
        {
            return $this->call('metaWeblog.getRecentPosts', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password,
                (integer)$limit
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Update a given post using metaWeblog.newPost
     * More infos : http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.editPost
     *
     * @param integer $id post id
     * @param string $title title of the post
     * @param string $excerpt excerpt of the post
     * @param string $content content of the post
     * @param boolean $publish publish Yes/No
     * @return Mixed <boolean, integer> Return false if failure or Post ID if success
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function updatePost($id=null, $title=null, $excerpt=null, $content=null, $publish=false, $slug=null, $categoryId=null)
    {
        if ($id === NULL)
            throw new InvalidArgumentException('post id is missing');

        if ($title === NULL)
            throw new InvalidArgumentException('post title is missing');

        if ($content === NULL)
            throw new InvalidArgumentException('post content is missing');

        try
        {
            return $this->call('metaWeblog.editPost', Array(
                (string)$id,
                (string)$this->username,
                (string)$this->password,
                Array(
                    'title' => $title,
                    'mt_excerpt' => $excerpt,
                    'description' => $content,
                    'wp_slug' => $slug,
                    'categories' => Array(Array('categoryId'=>(string)$categoryId)),
                    'post_type' => 'post'
                ),
                $publish,
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Delete a given post
     * More infos : http://codex.wordpress.org/XML-RPC_Blogger_API#blogger.deletePost
     *
     * @param integer $id
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function deletePost($id=null)
    {
        if ($id === NULL)
            throw new InvalidArgumentException('post id is missing');

        try
        {
            return $this->call('blogger.deletePost', Array(
                (string)'', // string appkey: Not applicable for DotClear, can be any value and will be ignored.
                (string)$id,
                (string)$this->username,
                (string)$this->password,
                (integer)1 // bool publish: Will be ignored
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
         
    }

    /**
     * Attach a category to a given post
     * More infos: http://codex.wordpress.org/XML-RPC_MovableType_API#mt.setPostCategories
     *
     * @param integer $postId
     * @param integer $categoryId
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function setPostCategory($postId=null, $categoryId=null)
    {
        if ($postId === NULL)
            throw new InvalidArgumentException('post id is missing');
         
        if ($categoryId === NULL)
            throw new InvalidArgumentException('category id is missing');

        try
        {
            return $this->call('mt.setPostCategories',
                Array(
                    (string)$postId,
                    (string)$this->username,
                    (string)$this->password,
                    Array(Array('categoryId'=>(string)$categoryId))
                ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Create a new category
     * More infos : http://codex.wordpress.org/XML-RPC_wp#wp.newCategory
     *
     * @param string $name name of the new category
     * @param string $slug slug of the new category
     * @param string $description description of the new category
     * @param integer $parentId parent category id if any
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function createCategory($name=null, $slug=null, $description=null, $parentId=null)
    {
        if ($name === NULL)
            throw new InvalidArgumentException('category name is missing');
         
        if ($description === NULL)
            throw new InvalidArgumentException('category description is missing');

        try
        {
            return $this->call('wp.newCategory', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password,
                Array(
                    'name' => (string)$name,
                    'slug' => (string)$slug,
                    'category_description' => (string)$description,
                    'category_parent' => (string)$parentId,
                ),
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get categories
     * More infos : http://codex.wordpress.org/XML-RPC_wp#wp.getCategories
     *
     * @throws Exception
     */
    public function getCategories()
    {
        try
        {
            return $this->call('wp.getCategories', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Delete a category
     * More infos : http://codex.wordpress.org/XML-RPC_wp#wp.deleteCategory
     *
     * @param integer $categoryId
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function deleteCategory($id=null)
    {
        if ($id === NULL)
            throw new InvalidArgumentException('category id is missing');
         
        try
        {
            return $this->call('wp.deleteCategory', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password,
                (string)$id
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get blog authors
     * More infos: http://codex.wordpress.org/XML-RPC_wp#wp.getAuthors
     *
     * @throws Exception
     * @return array
     */
    public function getAuthors()
    {
        try
        {
            return $this->call('wp.getAuthors', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Upload a file to the blog
     * More infos : http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.newMediaObject
     *
     * @param string $filename
     * @param string $uri media URI (full url or path)
     * @return mixed <boolean, array> false if failed, file data if success (file name, url and type)
     */
    public function newMedia($filename=null, $uri=null)
    {
        if ($filename === NULL)
            throw new InvalidArgumentException('Given filename is wrong');
         
        // Check if file exists
        if ($uri === NULL || !file_exists($uri))
            throw new InvalidArgumentException('File not found');

        // get the bits of the media file
        $filetype = pathinfo($uri, PATHINFO_EXTENSION);
        $file = fopen($uri, 'rb');
        $filesize = filesize($uri);
        $filedata = fread($file, $filesize);
        fclose($file);
         
        try
        {
            return $this->call('metaWeblog.newMediaObject', Array(
                (string)$this->blogId,
                (string)$this->username,
                (string)$this->password,
                Array(
                    'name' => $filename,
                    'bits' => base64_encode($filedata), // base64-encoded binary data
                )
            ));
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    private function call($method=null, $parameters=Array())
    {
        if (is_null($method))
            throw new InvalidArgumentException('No method');

        if (!is_array($parameters))
            throw new InvalidArgumentException('Wrong parameters');

        try
        {
            return $this->blog->call($method, $parameters);
        }
        catch(\fXmlRpc\Exception\HttpException $e)
        {
            throw new Exception('(HTTP) Communication failed. Is the remote URL correct?', null, $e);
        }
        catch(\fXmlRpc\Exception\InvalidArgumentException $e)
        {
            throw new Exception('Wrong argument', null, $e);
        }
        catch(\fXmlRpc\Exception\MissingExtensionException $e)
        {
            throw new Exception('Error: missing extension. Aborted', null, $e);
        }
        catch(\fXmlRpc\Exception\ResponseException $e)
        {
            throw new Exception('Wrong response', null, $e);
        }
        catch(\fXmlRpc\Exception\RuntimeException $e)
        {
            throw new \RuntimeException('Runtime error', null, $e);
        }
        catch(\fXmlRpc\Exception\SerializationException $e)
        {
            throw new Exception('Passed data are corrupted', null, $e);
        }
        catch(\fXmlRpc\Exception\TcpException $e)
        {
            throw new Exception('(TCP) Communication failed', null, $e);
        }
        catch(\fXmlRpc\Exception\TransportException $e)
        {
            throw new Exception('Communication failed', null, $e);
        }
    }

}
