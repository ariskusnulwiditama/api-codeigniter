<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Post extends ResourceController
{
    protected $modelName = 'App\Models\PostModel';
    protected $format = 'json';

    public function index()
    {
        return $this->respond([
            'statusCode'    => 200,
            'message'       => 'OK',
            'data'          => $this->model->orderBy('id', 'DESC')->findAll()
        ], 200);
    }

    public function show($id=null)
    {
        return $this->respond([
            'statusCode'    => 200,
            'message'       => 'OK',
            'data'          => $this->model->find($id)
        ], 200);
    }

    public function create()
    {
        if($this->request){
            //get request from frontend
            if($this->request->getJSON()){
                $json = $this->request->getJSON();
                $post = $this->model->insert([
                    'title'     => $json->title,
                    'content'   => $json->content
                ]);
            }else{
                //get request from postman
                $post = $this->model->insert([
                    'title'     => $this->request->getPost('title'),
                    'content'   => $this->request->getPost('content')
                ]);
            }

            return $this->respond([
                'statusCode'    => 201,
                'message'       => 'Data berhasil disimpan' 
            ], 201);
        }
    }

    public function update($id=null)
    {
        //model
        $post = $this->model;

        if($this->request){
            //get request from frontend
            if($this->request->getJSON()){
                $json = $this->request->getJSON();
                $post->update($json->id, [
                    'title' => $json->title,
                    'content' => $json->content
                ]);
            }else{
                //get request from postman
                $data = $this->request->getRawInput();
                $post->update($id, $data);
            }

            return $this->respond([
                'statusCode' => 200,
                'message' => 'Data berhasil diupdate'
            ], 200);
        }
    }

    public function delete($id=null)
    {
        $post = $this->model->find($id);

        if($post){
            $this->model->delete($id);

            return $this->respond([
                'statusCode' => 200,
                'message' => 'Data berhasil dihapus'
            ], 200);
        }
    }
}
