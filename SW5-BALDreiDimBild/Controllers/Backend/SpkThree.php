<?php

use Shopware\Components\CSRFWhitelistAware;

/*
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Shopware_Controllers_Backend_SpkThree extends Enlight_Controller_Action implements CSRFWhitelistAware
{

    public function preDispatch()
    {
        $this->get('template')->addTemplateDir(__DIR__ . '/../../Resources/views/');
    }

    public function postDispatch()
    {
        $csrfToken = $this->container->get('BackendSession')->offsetGet('X-CSRF-Token');
        $this->View()->assign([ 'csrfToken' => $csrfToken ]);
    }

    public function indexAction()
    {
       $this->getList();
    }

    private function getList(){
        try {
            $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
            $queryBuilder->select('*')
                ->from('three');

            $suppliers = $queryBuilder->execute()->fetchAll();
            $total = count($suppliers);

            $this->View()->assign(['suppliers' => $suppliers, 'totalSuppliers' => $total]);
        }
        catch (Exception $error){
            $this->jsondata($this->error->getMessage(),false);
        }
    }

    public function deleteAction(){
       $id=$this->Request()->query->get('id');
       try{
           $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
           $queryBuilder->delete('three')
               ->where('id = :id')
               ->setParameter(':id', $id)
               ->execute();

       }catch (Exception $error){
           $this->jsondata($error->getMessage(),false);
       }
        $this->redirect([
            'controller' => 'SpkThree',
            'action'    => 'list'
        ]);

    }
    public function uploadAction()
    {
        $structure="media/upload/three/";
        if(isset($_FILES) and isset($_POST)){
            if(!empty($_FILES) and !empty($_POST)){
                $image_name=$_FILES['file']['name'];
                $name=$_POST['fileId'];
                $valid_extensions=['glb','gltf'];
                $extension=pathinfo($image_name,PATHINFO_EXTENSION);
                if(in_array($extension,$valid_extensions)){
                    $uploadPath=$structure.time().'.'.$extension;
                    if (!is_dir($structure)) {
                        if (!mkdir($structure, 0777, true)) {
                            $message="Das Erstellen von Verzeichnissen ist fehlgeschlagen.";
                            $this->jsondata($message,false);
                        }
                    }
                    if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadPath)){
                        $message="File Uploaded";
                       // $this->jsondata($message,true);
                        $queryBuilder = $this->get('dbal_connection')->createQueryBuilder();
                        try{
                            $queryBuilder
                                ->insert('three')
                                ->setValue('name', ':name')
                                ->setValue('path', ':path')
                                ->setParameter(':name', $name)
                                ->setParameter(':path', "/".$uploadPath)
                                ->execute();
                            $this->jsondata($message,true);
                        }catch (Exception $error){
                            $this->jsondata($error->getMessage(),false);
                        }

                    }else{

                        $message='Impossible to copy the uploded File. Contact the administrator';
                        $this->jsondata($message,false);
                    }
                }else{
                    $message='Only gltf or glb allowed to upload ' ;
                    $this->jsondata($message,false);

                }
            }
        }else{
            $message='Init';
            $this->jsondata($message,false);
        }

    }

    /**
     * @param $message
     * @return false|string
     */
    public function jsondata($message,$success){
        $build=[
            'success'=> $success,
            'error'=>$message=="Init"?$success:!$success,
            'data'=> [
                'message'=>$message,
            ],
            'total'=> 0
        ];
        $this->View()->assign(['data'=>$build]);
    }

    public function listAction()
    {
        $this->getList();
    }

    public function getWhitelistedCSRFActions()
    {
        return ['index'];
    }
}
