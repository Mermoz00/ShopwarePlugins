{extends file="parent:frontend/detail/content.tpl"}
{* {block name="frontend_detail_index_header_container"}
    {$smarty.block.parent}
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>
    {if $sArticle['three'] }
        <details>
            <summary id="summary">das Bild in 3D anzeigen </summary>
            <!-- Use it like any other HTML element | This is the actualy 3D-Object -->
            <div class="d-flex justify-content-center">
              {if $threeData['image'] }
                <model-viewer id="box" src='{$sArticle['three']}' style="background-image:url({$threeData['image']});" alt="A 3D model of an astronaut" auto-rotate camera-controls></model-viewer>
              {else }
                <model-viewer id="box" src='{$sArticle['three']}' style="background-color:{$threeData['color']};" alt="A 3D model of an astronaut" auto-rotate camera-controls></model-viewer>
              {/if}
            </div>
        </details>
    {/if} *}
{* {/block} *}


{* {block name='frontend_index_content_inner'}
    {$smarty.block.parent}
    

     <div class="product--detail-upper block-group">
            {* Product image *}
            {* {block name='frontend_detail_index_image_container'}
                <div class="product--image-container image-slider{if $sArticle.image && {config name="sUSEZOOMPLUS"}} product--image-zoom{/if}"
                    {if $sArticle.image}
                    data-image-slider="true"
                    data-image-gallery="true"
                    data-maxZoom="{$theme.lightboxZoomFactor}"
                    data-thumbnails=".image--thumbnails"
                    {/if}>
                    
                {if $sArticle['three'] }
                <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
                <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>
                <details>
                <summary id="summary">das Bild in 3D anzeigen </summary>
                <!-- Use it like any other HTML element | This is the actualy 3D-Object -->
                <div class="d-flex justify-content-center">
                    {if $threeData['image'] }
                        <model-viewer id="box" src='{$sArticle['three']}' style="background-image:url({$threeData['image']});" alt="A 3D model of an astronaut" auto-rotate camera-controls></model-viewer>
                    {else }
                        <model-viewer id="box" src='{$sArticle['three']}' style="background-color:{$threeData['color']};" alt="A 3D model of an astronaut" auto-rotate camera-controls></model-viewer>
                    {/if}
                </div>
                </details>
                    {/if}

                    {block name="frontend_detail_index_image"}
                        {include file="frontend/detail/image.tpl"}
                    {/block}
                </div>
            {/block}
{/block} *} 

