<?php
use Shopware\Models\Article\Article;
use Symfony\Component\HttpFoundation\json_encode;

class Shopware_Controllers_Backend_SpkExtendBackend extends Shopware_Controllers_Backend_Application
{
    protected $model = Article::class;

    protected $alias = 'product';

    /**
     * {@inheritdoc}
     */
    protected function getListQuery()
    {
        try{
            if(isset($_POST)){
                if(!empty($_POST)) {
                    $builder = $this->get('dbal_connection')->createQueryBuilder();
                    $builder->update('s_articles_attributes')
                        ->set('three', ':path')
                        ->where('articledetailsID = :id')
                        ->setParameters([
                            'path' => $_POST['three'],
                            'id' => $_POST['fileId'],
                        ]);
                    $builder->execute();

                    $this->jsondata($_POST['three'], true);
                }
            }
        }catch (Exception $error){
            $this->jsondata($error->getMessage(),false);
        }
    }

    /**
     * @param $message
     * @param $success
     * @return false|string
     */
    public function jsondata($message,$success){
        die( json_encode([
            'success'=> $success,
            'data'=> [
                'message'=>$message,
            ],
            'total'=>count($message)
        ]));
    }
}
