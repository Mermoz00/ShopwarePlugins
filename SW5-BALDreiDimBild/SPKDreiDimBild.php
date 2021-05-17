<?php

namespace SPKDreiDimBild;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\SchemaTool;
use Enlight_Controller_ActionEventArgs;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Theme\LessDefinition;
use Shopware\Models\Media\Media;
use SPKDreiDimBild\Models\Three;

/**
 * This plugin also adds 3D Bild  to the product.
 *
 * @package SPKDreiDimBild
 */
class SPKDreiDimBild extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch',
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_Three' => 'onGetBackendController',
            'Theme_Compiler_Collect_Plugin_Less' => 'addLessFiles',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles',
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Article' => 'extendArticleModule',
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail'=>'onFrontendPostDispatchDetails'
        ];
    }
    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatch(Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $view->addTemplateDir($this->getPath() . '/Resources/views');
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatchDetails(Enlight_Controller_ActionEventArgs $args){
        $view = $args->getSubject()->View();
        $view->addTemplateDir($this->getPath() . '/Resources/views');
        $categoryIds=$view->getAssign('sBreadcrumb');
        $id=null;
        $image=null;
        $color="";
       for($i=count($categoryIds)-1;$i>=0;$i--){
            $id=Shopware()->Modules()->Categories()->sGetCategoryContent($categoryIds[$i]["id"])["attribute"]["three_bg_image"];
            $color=Shopware()->Modules()->Categories()->sGetCategoryContent($categoryIds[$i]["id"])["attribute"]["three_bg_color"];
           if(!is_null($id)){
               $img=$this->smartyModifierMediaurl($id);
               if (!is_null($img)){
                   $image=$this->checkIfBgImageExist($img);
               }
           }
           if(!is_null($image) || !is_null($color)){
               $view->assign('threeData',[
                   'image'=>$image,
                   'color'=>$color
               ]);
               break;
           }

        }
        if(is_null($image) && $color==""){
            $baseId=Shopware()->Modules()->Categories()->baseId;
            $id=Shopware()->Modules()->Categories()->sGetCategoryContent($baseId)["attribute"]["three_bg_image"];

            $color=Shopware()->Modules()->Categories()->sGetCategoryContent($baseId)["attribute"]["three_bg_color"];
            if(!is_null($id)){
                $img=$this->smartyModifierMediaurl($id);
                if (!is_null($img)){
                    $image=$this->checkIfBgImageExist($img);
                }
            }
            $view->assign('threeData',[
                'image'=>$image?$image:null,
                'color'=>$color
            ]);
        }
    }
    public function smartyModifierMediaurl($value)
    {
        if (is_numeric($value)) {
            $id = (int)$value;
            $media = Shopware()->Models()->getRepository('Shopware\Models\Media\Media')->findOneBy(['id' => $id]);
            if ($media) {
                $path = $media->getPath();

                return Shopware()->Container()->get('shopware_media.media_service')->getUrl($path);
            }
        }
        return null;
    }
    /**
     * @return string
     */
    public function onGetBackendController(): string
    {
        return __DIR__ . '/Controllers/Backend/Three.php';
    }
    /**
     * @return ArrayCollection
     */
    public function addLessFiles(): ArrayCollection
    {
        $less = new LessDefinition([], [__DIR__ . '/Resources/frontend/less/all.less']);
        return new ArrayCollection([$less]);
    }

    /**
     * @return ArrayCollection
     */
    public function addJsFiles(): ArrayCollection
    {
        $jsFiles = [__DIR__ . '/Resources/frontend/js/jquery.main.js'];
        return new ArrayCollection($jsFiles);
    }

    /**
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $this->installSchema();
        $attributeCrudService = $this->container->get('shopware_attribute.crud_service');
        $attributeCrudService->update(
            's_articles_attributes',
            'three',
            TypeMapping::TYPE_STRING,
            [
                'displayInBackend' => true,
                'position' => 1,
                'custom' => false,
                'label'=> '3D Bild-path'
            ],
            null,
            true
        );
        $attributeCrudService->update(
            's_categories_attributes',
            'three_bg_image',
            TypeMapping::TYPE_SINGLE_SELECTION,
            [
                'entity'           => Media::class,
                'displayInBackend' => true,
                'position' => 1,
                'custom' => false,
                'label'=> '3D Hintergrundbild'
            ],
            null,
            true
        );
        $attributeCrudService->update(
            's_categories_attributes',
            'three_bg_color',
            TypeMapping::TYPE_STRING,
            [
                'displayInBackend' => true,
                'position' => 2,
                'custom' => false,
                'label'=> '3D Hintergrundfarbe'
            ],
            null,
            true
        );
    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        parent::update($context);
        $this->installSchema();
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        if ($context->keepUserData()) {
            return;
        }
        parent::uninstall($context);
        $this->uninstallSchema();
    }

    /**
     * Install or update profile table
     */
    private function installSchema()
    {
        $tool = new SchemaTool($this->container->get('models'));

        $tool->updateSchema([$this->container->get('models')->getClassMetadata(Three::class)], true);
    }

    /**
     * Remove profile table
     */
    private function uninstallSchema()
    {
        $em=$this->container->get('models');
        $tool = new SchemaTool($em);

        $tool->dropSchema([$this->container->get('models')->getClassMetadata(Three::class)]);
        $this->deleteArticleAttributes();
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function extendArticleModule(Enlight_Controller_ActionEventArgs $args)
    {
        /** @var  $request */
        $request = $args->getSubject()->Request();

        /** @var $view */
        $view = $args->getSubject()->View();

        // register templates
        $view->addTemplateDir($this->getPath() . '/Resources/views');

        if ($request->getActionName() === 'load') {
            $view->extendsTemplate('backend/article/spk_extend_backend/view/detail/window.js');
        }
    }
    private function deleteArticleAttributes()
    {
        $service = $this->container->get('shopware_attribute.crud_service');

        $attributeSubtitleExist = $service->get('s_articles_attributes', 'three');

        if($attributeSubtitleExist){
            $service->delete(
                's_articles_attributes',
                'three'
            );
        }
        $attributeSubtitleExist = $service->get('s_categories_attributes', 'three_bg_image');

        if($attributeSubtitleExist){
            $service->delete(
                's_categories_attributes',
                'three_bg_image'
            );
        }
        $attributeSubtitleExist = $service->get('s_categories_attributes', 'three_bg_color');

        if($attributeSubtitleExist){
            $service->delete(
                's_categories_attributes',
                'three_bg_color'
            );
        }

        // $context->scheduleClearCache(InstallContext::CASH_LIST_ALL);

    }
    public function checkIfBgImageExist($path): ?string
    {
        if(is_null($path)){
            return null;
        }else{
            return "/media".explode('media', $path, 2)[1];
        }

    }
}
