<?php namespace Spir\Dotclear;

interface ClientInterface
{

	/**
	 * Set the XML-RPC url
	 *
	 * @param string $url
	 * @throws InvalidArgumentException
	 */
	public function setUrl($url=null);
	
	/**
	 * Set the blog username
	 *
	 * @param string $username
	 * @throws InvalidArgumentException
	 */
	public function setUsername($username=null);
	
	/**
	 * Set the blog password
	 *
	 * @param string $password
	 * @throws InvalidArgumentException
	 */
	public function setPassword($password=null);
	
	/**
	 * Set the blog id to discuss with
	 *
	 * @param string $blogId
	 * @throws InvalidArgumentException
	 */
	public function setBlogId($blogId=null);
	
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
	public function createPost($title=null, $excerpt=null, $content=null, $publish=false, $slug=null, $categoryId=null);
	
	/**
	 * Return all pages/posts from a selected blog
	 * More infos: http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.getPost
	 *
	 * @param integer $id
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function getPost($id=null);
	
	/**
	 * Get all recent posts
	 * More infos: http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.getRecentPosts
	 *
	 * @param integer $limit maximum post to retrieve
	 * @throws Exception
	 */
	public function getPosts($limit=10);
	
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
	public function updatePost($id=null, $title=null, $excerpt=null, $content=null, $publish=false, $slug=null, $categoryId=null);
	
	/**
	 * Delete a given post
	 * More infos : http://codex.wordpress.org/XML-RPC_Blogger_API#blogger.deletePost
	 *
	 * @param integer $id
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function deletePost($id=null);
	
	/**
	 * Attach a category to a given post
	 * More infos: http://codex.wordpress.org/XML-RPC_MovableType_API#mt.setPostCategories
	 * 
	 * @param integer $postId
	 * @param integer $categoryId
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function setPostCategory($postId=null, $categoryId=null);
	
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
	public function createCategory($name=null, $slug=null, $description=null, $parentId=null);
	
	/**
	 * Get categories
	 * More infos : http://codex.wordpress.org/XML-RPC_wp#wp.getCategories
	 * 
	 * @throws Exception
	 */
	public function getCategories();
	
	/**
	 * Delete a category
	 * More infos : http://codex.wordpress.org/XML-RPC_wp#wp.deleteCategory
	 * 
	 * @param integer $categoryId
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function deleteCategory($id=null);
	
	/**
	 * Get blog authors
	 * More infos: http://codex.wordpress.org/XML-RPC_wp#wp.getAuthors
	 * 
	 * @throws Exception
	 * @return array
	 */
	public function getAuthors();

	/**
	 * Upload a file to the blog
	 * More infos : http://codex.wordpress.org/XML-RPC_MetaWeblog_API#metaWeblog.newMediaObject
	 * 
	 * @param string $filename
	 * @param string $uri media URI (full url or path)
	 * @return mixed <boolean, array> false if failed, file data if success (file name, url and type)
	 */
	public function newMedia($filename=null, $uri=null);
	
}
