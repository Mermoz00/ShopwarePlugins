<?php
declare(strict_types=1);

namespace BAL\DreiDimBild\Core\Api;


use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Page\Product\ProductPageLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;

/**
 * @RouteScope(scopes={"storefront"})
 */
class ThreeController extends StorefrontController
{
    /**
     * @var ProductPageLoader
     */
    private $productPageLoader;
    private $structure="upload/three/";
    public function __construct(ProductPageLoader $productPageLoader) {
        $this->productPageLoader = $productPageLoader;
    }
    /**
     * @Route("/upload/file", name="three.upload", defaults={"csrf_protected"=false},methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        if($request->files->get('file')){
            $image_name=$_FILES['file']['name'];
            $valid_extensions=array('glb','gltf');
            $extension=pathinfo($image_name,PATHINFO_EXTENSION);
            if(in_array($extension,$valid_extensions)){

                if (!is_dir($this->structure)) {
                    if (!mkdir($this->structure, 0777, true)) {
                        $message="Das Erstellen von Verzeichnissen ist fehlgeschlagen.";
                        return new JsonResponse(['message'=>$message],Response::HTTP_FORBIDDEN);
                    }
                }
                $upload_path= $this->structure.time().'.'.$extension;
                if(move_uploaded_file($_FILES['file']['tmp_name'],$upload_path)){
                    $message='File Uploaded';
                    $file=$upload_path;
                }else{
                    $message='Error with Upload. Try again.';
                    return new JsonResponse(['message'=>$message],Response::HTTP_FORBIDDEN);
                }
            }else{
                $message='Only gltf or glb allowed to upload';
                return new JsonResponse(['message'=>$message],Response::HTTP_FORBIDDEN);

            }
        }else{
            $message='Select File';
            return new JsonResponse(['message'=>$message],Response::HTTP_FORBIDDEN);
        }
        $output=array(
            'message'=>$message,
            'file'=>'/'.$file,
        );
        return new JsonResponse($output);
    }

}
