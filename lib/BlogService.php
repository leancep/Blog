<?php 
namespace Library;

class BlogService {
  
    public function savePost(string $content, string $user) {
      $fs = new \Library\FileStore($user . '.posts');
      $posts = $fs->read();
      $posts[]=$content;
      $res=$fs->save($posts);
      return $res;
    }
    
    public function getAllPosts(string $user) { 
      $fs = new \Library\FileStore($user . '.posts');
      $posts = $fs->read();
      return $posts;
    }
  }